<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Exam;

class ExamController extends Controller
{
    /**
     * GET /api/exams
     *
     * List all available exams.
     */
    public function index()
    {
        $exams = Exam::orderBy('sort_order')
            ->select('id', 'slug', 'name', 'name_nepali', 'description', 'is_featured')
            ->get();

        return response()->json(['exams' => $exams]);
    }

    /**
     * GET /api/exams/{slug}
     *
     * Get a single exam by slug.
     */
    public function show(string $slug)
    {
        $exam = Exam::where('slug', $slug)->firstOrFail();

        return response()->json(['exam' => $exam]);
    }
}
