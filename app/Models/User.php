<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'firebase_uid',
        'phone',
        'email',
        'name',
        'language',
        'preferred_voice',
        'current_exam_id',
        'credits',
        'total_credits_purchased',
        'total_credits_spent',
        'referred_by_teacher_id',
        'referred_by_user_id',
        'first_purchase_at',
        'otp_bonus_granted',
        'onboarding_completed',
    ];

    protected $hidden = [
        'firebase_uid',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'credits' => 'integer',
            'total_credits_purchased' => 'integer',
            'total_credits_spent' => 'integer',
            'otp_bonus_granted' => 'boolean',
            'onboarding_completed' => 'boolean',
            'first_purchase_at' => 'datetime',
        ];
    }

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    public function currentExam()
    {
        return $this->belongsTo(Exam::class, 'current_exam_id');
    }

    public function referringTeacher()
    {
        return $this->belongsTo(Teacher::class, 'referred_by_teacher_id');
    }

    public function referringUser()
    {
        return $this->belongsTo(User::class, 'referred_by_user_id');
    }

    public function creditTransactions()
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Check if user has enough credits for an action.
     */
    public function hasCredits(int $amount): bool
    {
        return $this->credits >= $amount;
    }

    /**
     * Check if a chapter is unlocked for this user in a given mode.
     */
    public function hasUnlockedChapter(int $chapterId, string $mode): bool
    {
        return $this->chapterUnlocks()
            ->where('chapter_id', $chapterId)
            ->where('mode', $mode)
            ->exists();
    }
}
