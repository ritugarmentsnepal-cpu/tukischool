<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Tuki School
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/firebase-login', [AuthController::class, 'firebaseLogin']);

// Public exam listing
Route::get('/exams', [ExamController::class, 'index']);
Route::get('/exams/{slug}', [ExamController::class, 'show']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Credits (Sprint 8)
    // Route::get('/credits/balance', [CreditController::class, 'balance']);
    // Route::get('/credits/packs', [CreditController::class, 'packs']);
    // Route::post('/credits/purchase', [CreditController::class, 'purchase']);

    // Syllabus (Sprint 3)
    // Route::post('/syllabi/upload', [SyllabusController::class, 'upload']);
    // Route::get('/syllabi/{id}/status', [SyllabusController::class, 'status']);

    // Chapters (Sprint 3+)
    // Route::get('/chapters/{syllabusId}', [ChapterController::class, 'index']);
    // Route::post('/chapters/{id}/unlock', [ChapterController::class, 'unlock']);
    // Route::get('/chapters/{id}/content/{mode}', [ContentController::class, 'show']);

    // Q&A (Sprint 7)
    // Route::post('/qa/start-session', [QaController::class, 'startSession']);
    // Route::post('/qa/turn', [QaController::class, 'turn']);
    // Route::post('/qa/end-session', [QaController::class, 'endSession']);
});
