<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('source', ['pre_loaded', 'user_upload'])->default('user_upload');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->longText('raw_text')->nullable();
            $table->unsignedInteger('chapter_count')->default(0);
            $table->unsignedInteger('credits_spent')->default(0);
            $table->timestamps();

            $table->index(['exam_id', 'source']);
            $table->index(['user_id', 'status']);
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('syllabus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order')->default(0);
            $table->string('title');
            $table->string('title_nepali')->nullable();
            $table->enum('mode', ['full', 'revision'])->default('full');
            $table->enum('status', ['locked', 'generating', 'ready', 'failed'])->default('locked');
            $table->longText('textbook_content')->nullable();
            $table->longText('explanation_content')->nullable();
            $table->unsignedInteger('word_count')->default(0);
            $table->unsignedInteger('credits_to_unlock')->default(5);
            $table->foreignId('unlocked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            $table->index(['syllabus_id', 'order']);
            $table->index(['exam_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('syllabi');
    }
};
