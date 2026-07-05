<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationToken extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'verification_token',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Get the user that owns the token.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a new 6-digit OTP.
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a unique verification token.
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if the OTP is expired.
     */
    public function isExpired(): bool
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Mark the OTP as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Create a new OTP for a user.
     */
    public static function createForUser(int $userId): self
    {
        // Delete any existing unused tokens for this user
        static::where('user_id', $userId)
            ->where('is_used', false)
            ->delete();

        return static::create([
            'user_id' => $userId,
            'otp' => static::generateOtp(),
            'verification_token' => static::generateVerificationToken(),
            'expires_at' => Carbon::now()->addMinutes(15), // OTP valid for 15 minutes
            'is_used' => false,
        ]);
    }

    /**
     * Verify OTP for a user.
     */
    public static function verifyOtp(int $userId, string $otp): bool
    {
        $token = static::where('user_id', $userId)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->first();

        if (!$token || $token->isExpired()) {
            return false;
        }

        $token->markAsUsed();
        return true;
    }

    /**
     * Verify token for link-based verification.
     */
    public static function verifyToken(string $verificationToken): ?self
    {
        $token = static::where('verification_token', $verificationToken)
            ->where('is_used', false)
            ->first();

        if (!$token || $token->isExpired()) {
            return null;
        }

        return $token;
    }
}
