<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Syllabus;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SyllabusController extends Controller
{
    public function __construct(
        private GeminiService $geminiService
    ) {}

    /**
     * GET /api/syllabi/{examId}
     *
     * Get all syllabi for an exam (pre-loaded + user's own).
     */
    public function index(Request $request, int $examId)
    {
        $user = $request->user();

        $syllabi = Syllabus::with('chapters')
            ->where('exam_id', $examId)
            ->where(function ($q) use ($user) {
                $q->where('source', 'pre_loaded')
                  ->orWhere('user_id', $user->id);
            })
            ->get();

        return response()->json([
            'syllabi' => $syllabi->map(fn ($s) => [
                'id' => $s->id,
                'title' => $s->title,
                'source' => $s->source,
                'status' => $s->status,
                'chapter_count' => $s->chapters->count(),
            ]),
        ]);
    }

    /**
     * POST /api/syllabi/upload
     *
     * Upload syllabus text and parse into chapters using Gemini.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'title' => 'required|string|max:255',
            'raw_text' => 'required|string|min:20',
        ]);

        $user = $request->user();

        // Create syllabus record
        $syllabus = Syllabus::create([
            'user_id' => $user->id,
            'exam_id' => $request->exam_id,
            'title' => $request->title,
            'source' => 'user_upload',
            'status' => 'processing',
            'raw_text' => $request->raw_text,
        ]);

        // Parse with Gemini
        try {
            $examName = $syllabus->exam->name ?? 'Exam';
            $chapters = $this->geminiService->parseSyllabus($request->raw_text, $examName);

            if (empty($chapters)) {
                $syllabus->update(['status' => 'failed']);
                return response()->json([
                    'error' => 'parse_failed',
                    'message' => 'Could not parse the syllabus. Please try with clearer text.',
                ], 422);
            }

            // Create chapter records
            foreach ($chapters as $i => $ch) {
                $syllabus->chapters()->create([
                    'exam_id' => $request->exam_id,
                    'order' => $i + 1,
                    'title' => $ch['title'] ?? "Chapter " . ($i + 1),
                    'title_nepali' => $ch['title_nepali'] ?? null,
                    'credits_to_unlock' => $ch['credits_to_unlock'] ?? 5,
                    'status' => 'locked',
                ]);
            }

            $syllabus->update([
                'status' => 'completed',
                'chapter_count' => count($chapters),
            ]);

            return response()->json([
                'message' => 'Syllabus parsed successfully!',
                'syllabus_id' => $syllabus->id,
                'chapter_count' => count($chapters),
            ]);

        } catch (\Exception $e) {
            Log::error('Syllabus parsing failed', ['error' => $e->getMessage()]);
            $syllabus->update(['status' => 'failed']);

            return response()->json([
                'error' => 'parse_failed',
                'message' => 'An error occurred while parsing the syllabus.',
            ], 500);
        }
    }
}
