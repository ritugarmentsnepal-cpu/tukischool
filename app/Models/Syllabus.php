<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Syllabus extends Model
{
    protected $table = 'syllabi';

    protected $fillable = [
        'user_id',
        'exam_id',
        'title',
        'source',
        'status',
        'raw_text',
        'chapter_count',
        'credits_spent',
    ];

    protected $casts = [
        'chapter_count' => 'integer',
        'credits_spent' => 'integer',
    ];

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    // ── Scopes ──

    public function scopePreLoaded($query)
    {
        return $query->where('source', 'pre_loaded');
    }

    public function scopeForExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }
}
