<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    protected $fillable = [
        'syllabus_id',
        'exam_id',
        'order',
        'title',
        'title_nepali',
        'mode',
        'status',
        'textbook_content',
        'explanation_content',
        'word_count',
        'credits_to_unlock',
        'unlocked_by',
        'unlocked_at',
    ];

    protected $casts = [
        'order' => 'integer',
        'word_count' => 'integer',
        'credits_to_unlock' => 'integer',
        'unlocked_at' => 'datetime',
    ];

    // ── Relationships ──

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function unlockedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unlocked_by');
    }

    // ── Helpers ──

    public function isUnlocked(): bool
    {
        return $this->status === 'ready' || $this->status === 'generating';
    }

    public function isLocked(): bool
    {
        return $this->status === 'locked';
    }

    public function hasContent(): bool
    {
        return $this->status === 'ready' && $this->textbook_content !== null;
    }
}
