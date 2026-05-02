<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta';
    private string $model = 'gemini-2.0-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key', '');
    }

    /**
     * Parse raw syllabus text into structured chapters.
     */
    public function parseSyllabus(string $rawText, string $examName): array
    {
        $prompt = <<<PROMPT
You are an expert Nepali education curriculum designer. Parse the following syllabus text into a structured list of chapters.

Exam: {$examName}
Syllabus Text:
---
{$rawText}
---

Return a JSON array of chapters. Each chapter should have:
- "title": English title
- "title_nepali": Nepali title (Devanagari script)
- "credits_to_unlock": estimated difficulty (3 for easy, 5 for medium, 8 for hard)

Return ONLY valid JSON array, no other text.
Example: [{"title": "Constitution of Nepal", "title_nepali": "नेपालको संविधान", "credits_to_unlock": 5}]
PROMPT;

        return $this->callGemini($prompt, true);
    }

    /**
     * Generate formal textbook content for a chapter.
     */
    public function generateTextbookContent(string $chapterTitle, string $examName): string
    {
        $prompt = <<<PROMPT
You are a Nepali competitive exam textbook author writing for "{$examName}".

Write a comprehensive, formal textbook chapter on: "{$chapterTitle}"

Guidelines:
- Write in a formal, academic tone suitable for Nepali competitive exams (Lok Sewa, bank exams)
- Include key facts, definitions, constitutional provisions, and important points
- Use bullet points and numbered lists for clarity
- Include relevant dates, names, and statistics where applicable
- Write approximately 800-1200 words
- Mix English and Nepali (Devanagari) where natural — key terms in Nepali, explanations can be in English
- Structure with clear headings and subheadings
- End with "Key Points to Remember" summary

Write the content now:
PROMPT;

        return $this->callGemini($prompt, false);
    }

    /**
     * Generate conversational Nepali explanation of the textbook content.
     * This is the "bujhaune" part — explaining like a coaching teacher.
     */
    public function generateExplanation(string $textbookContent, string $chapterTitle): string
    {
        $prompt = <<<PROMPT
You are a beloved Nepali coaching teacher (coaching sir) explaining a chapter to your students. Your students are preparing for competitive exams.

Chapter: "{$chapterTitle}"

Here is the formal textbook content:
---
{$textbookContent}
---

Now EXPLAIN this chapter conversationally, exactly how a Nepali coaching teacher would explain in class:
- Use colloquial Nepali (Romanized + Devanagari mix, like how teachers actually speak)
- Start with "अब यो chapter बुझौँ..." or similar
- Use phrases like "हेर्नुहोस्", "यो important छ", "exam मा यो आउँछ", "याद राख्नुहोस्"
- Give real-world examples and analogies that Nepali students relate to
- Be encouraging: "यो easy छ", "तपाईं बुझ्नुहुन्छ"
- Highlight what's most likely to come in exams
- Use a warm, approachable tone — like talking to a student sitting in front of you
- Approximately 600-900 words

Write the explanation now:
PROMPT;

        return $this->callGemini($prompt, false);
    }

    /**
     * Call the Gemini API.
     */
    private function callGemini(string $prompt, bool $parseJson): mixed
    {
        if (empty($this->apiKey)) {
            Log::warning('Gemini API key not configured');
            if ($parseJson) return [];
            return 'Gemini API key not configured. Please add GEMINI_API_KEY to your .env file.';
        }

        try {
            $response = Http::timeout(60)->post(
                "{$this->baseUrl}/models/{$this->model}:generateContent?key={$this->apiKey}",
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'topP' => 0.9,
                        'maxOutputTokens' => 4096,
                    ],
                ]
            );

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                if ($parseJson) return [];
                return 'Failed to generate content. Please try again.';
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

            if ($parseJson) {
                // Extract JSON from response (may be wrapped in ```json ... ```)
                $text = preg_replace('/^```json\s*/', '', $text);
                $text = preg_replace('/\s*```$/', '', $text);
                $text = trim($text);
                return json_decode($text, true) ?: [];
            }

            return $text;

        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['error' => $e->getMessage()]);
            if ($parseJson) return [];
            return 'An error occurred while generating content.';
        }
    }
}
