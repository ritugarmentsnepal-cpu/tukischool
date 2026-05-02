<?php

namespace App\Services;

use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Support\Facades\DB;

class CreditService
{
    /**
     * Grant bonus credits to a user (signup, OTP, referral, etc.)
     * Uses DB transaction to ensure atomicity.
     */
    public function grantBonus(User $user, int $amount, string $actionType, ?string $referenceId = null, ?array $metadata = null): CreditTransaction
    {
        return DB::transaction(function () use ($user, $amount, $actionType, $referenceId, $metadata) {
            // Lock the user row to prevent race conditions
            $user = User::lockForUpdate()->find($user->id);

            $newBalance = $user->credits + $amount;
            $user->credits = $newBalance;
            $user->save();

            return CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'bonus',
                'amount' => $amount,
                'action_type' => $actionType,
                'action_reference_id' => $referenceId,
                'balance_after' => $newBalance,
                'metadata' => $metadata,
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Deduct credits for a paid action.
     * Returns the transaction on success, throws exception if insufficient.
     */
    public function deduct(User $user, int $amount, string $actionType, ?string $referenceId = null, ?array $metadata = null): CreditTransaction
    {
        return DB::transaction(function () use ($user, $amount, $actionType, $referenceId, $metadata) {
            // Lock the user row — critical for preventing double-spend
            $user = User::lockForUpdate()->find($user->id);

            if ($user->credits < $amount) {
                throw new \App\Exceptions\InsufficientCreditsException(
                    "Need {$amount} credits, have {$user->credits}.",
                    $amount,
                    $user->credits
                );
            }

            $newBalance = $user->credits - $amount;
            $user->credits = $newBalance;
            $user->total_credits_spent += $amount;
            $user->save();

            return CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'spend',
                'amount' => -$amount, // Negative for debit
                'action_type' => $actionType,
                'action_reference_id' => $referenceId,
                'balance_after' => $newBalance,
                'metadata' => $metadata,
                'created_at' => now(),
            ]);
        });
    }

    /**
     * Add credits from a purchase.
     */
    public function addPurchase(User $user, int $totalCredits, string $packCode, string $txnId): CreditTransaction
    {
        return DB::transaction(function () use ($user, $totalCredits, $packCode, $txnId) {
            $user = User::lockForUpdate()->find($user->id);

            $newBalance = $user->credits + $totalCredits;
            $user->credits = $newBalance;
            $user->total_credits_purchased += $totalCredits;

            if (!$user->first_purchase_at) {
                $user->first_purchase_at = now();
            }

            $user->save();

            return CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'purchase',
                'amount' => $totalCredits,
                'action_type' => 'credit_purchase',
                'action_reference_id' => $txnId,
                'balance_after' => $newBalance,
                'metadata' => ['pack_code' => $packCode],
                'created_at' => now(),
            ]);
        });
    }
}
