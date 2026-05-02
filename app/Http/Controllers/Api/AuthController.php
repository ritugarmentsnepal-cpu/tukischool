<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        private CreditService $creditService
    ) {}

    /**
     * POST /api/auth/firebase-login
     *
     * Verify Firebase ID token from client, create or find user,
     * issue Sanctum token, grant signup bonus on first login.
     */
    public function firebaseLogin(Request $request)
    {
        $request->validate([
            'firebase_token' => 'required|string',
            'referral_code' => 'nullable|string|max:20',
        ]);

        try {
            // In production: verify Firebase ID token using kreait/firebase-php
            // For MVP dev: we'll accept the token and extract the phone number
            $auth = app('firebase.auth');
            $verifiedIdToken = $auth->verifyIdToken($request->firebase_token);
            $firebaseUid = $verifiedIdToken->claims()->get('sub');
            $phone = $verifiedIdToken->claims()->get('phone_number');
            $email = $verifiedIdToken->claims()->get('email');
            $name = $verifiedIdToken->claims()->get('name');

            if (!$phone && !$email) {
                return response()->json([
                    'error' => 'identifier_required',
                    'message' => 'Phone number or email is required for signup.',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Firebase auth failed', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'auth_failed',
                'message' => 'Firebase token verification failed.',
            ], 401);
        }

        // Find or create user
        $isNewUser = false;
        $user = User::where('firebase_uid', $firebaseUid)->first();

        if (!$user) {
            $isNewUser = true;
            $user = User::create([
                'firebase_uid' => $firebaseUid,
                'phone' => $phone,
                'email' => $email,
                'name' => $name,
                'language' => 'ne', // Default to Nepali
            ]);
        } else {
            // Update email/name if missing and now provided
            if (!$user->email && isset($email)) $user->email = $email;
            if (!$user->name && isset($name)) $user->name = $name;
            $user->save();
        }

        // Grant signup bonus for new users (100 credits)
        if ($isNewUser) {
            $this->creditService->grantBonus($user, 100, 'signup_bonus');
            $user->refresh();
        }

        // Grant OTP verification bonus (25 credits, one-time)
        if (!$user->otp_bonus_granted) {
            $this->creditService->grantBonus($user, 25, 'otp_verification_bonus');
            $user->otp_bonus_granted = true;
            $user->save();
            $user->refresh();
        }

        // Issue Sanctum token
        $token = $user->createToken('tuki-school-auth')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'email' => $user->email,
                'name' => $user->name,
                'language' => $user->language,
                'credits' => $user->credits,
                'onboarding_completed' => $user->onboarding_completed,
                'is_new_user' => $isNewUser,
            ],
        ]);
    }

    /**
     * POST /api/auth/logout
     *
     * Revoke the current Sanctum token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * GET /api/auth/me
     *
     * Get authenticated user's profile.
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'name' => $user->name,
                'language' => $user->language,
                'credits' => $user->credits,
                'total_credits_purchased' => $user->total_credits_purchased,
                'total_credits_spent' => $user->total_credits_spent,
                'current_exam_id' => $user->current_exam_id,
                'onboarding_completed' => $user->onboarding_completed,
            ],
        ]);
    }

    /**
     * PUT /api/auth/profile
     *
     * Update user profile (name, language, exam).
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:100',
            'language' => 'nullable|in:ne,en',
            'current_exam_id' => 'nullable|exists:exams,id',
        ]);

        $user = $request->user();
        $user->fill($request->only(['name', 'language', 'current_exam_id']));

        // Mark onboarding as completed when user sets name + exam
        if ($user->name && $user->current_exam_id && !$user->onboarding_completed) {
            $user->onboarding_completed = true;
            $user->save();

            // Grant onboarding completion bonus (25 credits)
            $this->creditService->grantBonus($user, 25, 'onboarding_completion_bonus');
            $user->refresh();

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'language' => $user->language,
                    'credits' => $user->credits,
                    'onboarding_completed' => $user->onboarding_completed,
                ],
                'bonus_granted' => 25,
            ]);
        }

        $user->save();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'language' => $user->language,
                'credits' => $user->credits,
                'onboarding_completed' => $user->onboarding_completed,
            ],
        ]);
    }
}
