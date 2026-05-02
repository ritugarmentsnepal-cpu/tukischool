<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid')->unique();
            $table->string('phone', 20)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('name')->nullable();
            $table->enum('language', ['ne', 'en'])->default('ne');
            $table->string('preferred_voice')->nullable();
            $table->unsignedBigInteger('current_exam_id')->nullable();

            // Credit system
            $table->integer('credits')->default(0);
            $table->integer('total_credits_purchased')->default(0);
            $table->integer('total_credits_spent')->default(0);

            // Referrals
            $table->unsignedBigInteger('referred_by_teacher_id')->nullable();
            $table->unsignedBigInteger('referred_by_user_id')->nullable();

            // Lifecycle
            $table->boolean('otp_bonus_granted')->default(false);
            $table->boolean('onboarding_completed')->default(false);
            $table->timestamp('first_purchase_at')->nullable();
            $table->timestamps();

            $table->index('phone');
            $table->index('firebase_uid');
            $table->index('referred_by_teacher_id');
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
