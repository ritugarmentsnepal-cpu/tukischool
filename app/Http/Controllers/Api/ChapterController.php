<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Syllabus;
use App\Services\CreditService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChapterController extends Controller
{
    public function __construct(
        private CreditService $creditService,
        private GeminiService $geminiService
    ) {}

    /**
     * GET /api/chapters/{syllabusId}
     *
     * List all chapters for a syllabus.
     */
    public function index(int $syllabusId)
    {
        $syllabus = Syllabus::with('chapters')->findOrFail($syllabusId);

        return response()->json([
            'syllabus' => [
                'id' => $syllabus->id,
                'title' => $syllabus->title,
                'status' => $syllabus->status,
                'chapter_count' => $syllabus->chapter_count,
            ],
            'chapters' => $syllabus->chapters->map(fn ($ch) => [
                'id' => $ch->id,
                'order' => $ch->order,
                'title' => $ch->title,
                'title_nepali' => $ch->title_nepali,
                'status' => $ch->status,
                'credits_to_unlock' => $ch->credits_to_unlock,
                'word_count' => $ch->word_count,
                'has_content' => $ch->hasContent(),
            ]),
        ]);
    }

    /**
     * POST /api/chapters/{id}/unlock
     *
     * Spend credits to unlock a chapter and generate AI content.
     */
    public function unlock(Request $request, int $id)
    {
        $user = $request->user();
        $chapter = Chapter::findOrFail($id);

        // Already unlocked?
        if ($chapter->status !== 'locked') {
            return response()->json([
                'message' => 'Chapter is already unlocked.',
                'chapter' => $this->formatChapter($chapter),
            ]);
        }

        // Check credits
        if (!$user->hasCredits($chapter->credits_to_unlock)) {
            return response()->json([
                'error' => 'insufficient_credits',
                'message' => 'You need ' . $chapter->credits_to_unlock . ' credits to unlock this chapter.',
                'credits_needed' => $chapter->credits_to_unlock,
                'credits_available' => $user->credits,
            ], 402);
        }

        // Deduct credits
        $this->creditService->deduct(
            $user,
            $chapter->credits_to_unlock,
            'chapter_unlock',
            (string) $chapter->id
        );

        // Mark as generating
        $chapter->status = 'generating';
        $chapter->unlocked_by = $user->id;
        $chapter->unlocked_at = now();
        $chapter->save();

        // Generate content with Gemini
        try {
            $examName = $chapter->exam->name ?? 'Lok Sewa';

            $textbook = $this->geminiService->generateTextbookContent(
                $chapter->title,
                $examName
            );

            $explanation = $this->geminiService->generateExplanation(
                $textbook,
                $chapter->title
            );

            $chapter->textbook_content = $textbook;
            $chapter->explanation_content = $explanation;
            $chapter->word_count = str_word_count(strip_tags($textbook . ' ' . $explanation));
            $chapter->status = 'ready';
            $chapter->save();

        } catch (\Exception $e) {
            Log::error('Chapter content generation failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage(),
            ]);
            $chapter->status = 'failed';
            $chapter->save();

            return response()->json([
                'error' => 'generation_failed',
                'message' => 'Content generation failed. Your credits have been spent. Please contact support.',
            ], 500);
        }

        $user->refresh();

        return response()->json([
            'message' => 'Chapter unlocked successfully!',
            'credits_spent' => $chapter->credits_to_unlock,
            'credits_remaining' => $user->credits,
            'chapter' => $this->formatChapter($chapter),
        ]);
    }

    /**
     * GET /api/chapters/{id}/content
     *
     * Get the textbook + explanation content for an unlocked chapter.
     */
    public function content(int $id)
    {
        $chapter = Chapter::with('exam')->findOrFail($id);

        if ($chapter->status === 'locked') {
            return response()->json([
                'error' => 'chapter_locked',
                'message' => 'This chapter is locked. Unlock it first.',
                'credits_to_unlock' => $chapter->credits_to_unlock,
            ], 403);
        }

        if ($chapter->status === 'generating') {
            return response()->json([
                'status' => 'generating',
                'message' => 'Content is being generated. Please wait...',
            ]);
        }

        return response()->json([
            'chapter' => $this->formatChapter($chapter),
            'content' => [
                'textbook' => $chapter->textbook_content,
                'explanation' => $chapter->explanation_content,
            ],
        ]);
    }

    /**
     * POST /api/chapters/{id}/ask
     *
     * Ask a question about a chapter. Gemini answers with chapter context.
     */
    public function ask(Request $request, int $id)
    {
        $request->validate([
            'question' => 'required|string|min:2|max:1000',
        ]);

        $chapter = Chapter::with('exam')->findOrFail($id);

        if (!$chapter->hasContent()) {
            return response()->json([
                'error' => 'no_content',
                'message' => 'This chapter has no content yet. Unlock it first.',
            ], 403);
        }

        try {
            $answer = $this->geminiService->askQuestion(
                $request->question,
                $chapter->title,
                $chapter->textbook_content,
                $chapter->exam->name ?? 'Exam'
            );

            return response()->json([
                'answer' => $answer,
                'chapter_id' => $chapter->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Q&A generation failed', [
                'chapter_id' => $chapter->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'generation_failed',
                'message' => 'Failed to generate answer. Please try again.',
            ], 500);
        }
    }

    private function formatChapter(Chapter $chapter): array
    {
        return [
            'id' => $chapter->id,
            'order' => $chapter->order,
            'title' => $chapter->title,
            'title_nepali' => $chapter->title_nepali,
            'status' => $chapter->status,
            'credits_to_unlock' => $chapter->credits_to_unlock,
            'word_count' => $chapter->word_count,
            'has_content' => $chapter->hasContent(),
        ];
    }
}
