<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\SyllabusController;
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

// Public chapter listing (for pre-loaded content)
Route::get('/chapters/{syllabusId}', [ChapterController::class, 'index']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::put('/auth/profile', [AuthController::class, 'updateProfile']);

    // Syllabi
    Route::get('/syllabi/{examId}', [SyllabusController::class, 'index']);
    Route::post('/syllabi/upload', [SyllabusController::class, 'upload']);

    // Chapters
    Route::post('/chapters/{id}/unlock', [ChapterController::class, 'unlock']);
    Route::get('/chapters/{id}/content', [ChapterController::class, 'content']);

    // Credits (Sprint 8)
    // Route::get('/credits/balance', [CreditController::class, 'balance']);
    // Route::post('/credits/purchase', [CreditController::class, 'purchase']);
});
