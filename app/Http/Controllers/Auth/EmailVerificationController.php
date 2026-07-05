<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerificationToken;
use App\Models\User;
use App\Mail\VerificationOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification form.
     */
    public function show(Request $request)
    {
        // If user is logged in and already verified, redirect based on role
        if (auth()->check() && auth()->user()->email_verified_at) {
            $user = auth()->user();
            if ($user->role === 'administrator') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'business_owner') {
                return redirect()->route('business-owner.dashboard');
            }
            return redirect()->route('home');
        }

        // Get email from query parameter (for non-authenticated users)
        $email = $request->query('email');

        return view('auth.verify-email', compact('email'));
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'email' => 'nullable|email', // For non-authenticated users
        ]);

        // Determine the user
        if (auth()->check()) {
            $user = auth()->user();
        } elseif ($request->email) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.',
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide your email address or log in to verify.',
            ], 422);
        }

        // Verify the OTP
        if (!EmailVerificationToken::verifyOtp($user->id, $request->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code. Please try again or request a new code.',
            ], 422);
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
        ]);

        // Determine redirect based on authentication status
        $redirectUrl = route('login');
        $message = 'Your email has been successfully verified! Please log in to continue.';

        if (auth()->check()) {
            // User is already logged in
            if ($user->role === 'administrator') {
                $redirectUrl = route('admin.dashboard');
            } elseif ($user->role === 'business_owner') {
                $redirectUrl = route('business-owner.dashboard');
            } else {
                $redirectUrl = route('home');
            }
            $message = 'Your email has been successfully verified! Redirecting...';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect' => $redirectUrl,
        ]);
    }

    /**
     * Resend verification OTP.
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email', // For non-authenticated users
        ]);

        // Determine the user
        if (auth()->check()) {
            $user = auth()->user();
        } elseif ($request->email) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found with this email address.',
                ], 404);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Please provide your email address or log in to resend verification code.',
            ], 422);
        }

        // Check if user is already verified
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Your email is already verified.',
            ], 422);
        }

        // Create new OTP token
        $token = EmailVerificationToken::createForUser($user->id);

        // Send verification email
        try {
            Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));
            Log::info("Verification email resent successfully to: {$user->email}");

            return response()->json([
                'success' => true,
                'message' => 'A new verification code has been sent to your email address. Please check your inbox.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend verification email: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification email. Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify email via link (token-based).
     */
    public function verifyViaLink(Request $request, $token)
    {
        // Verify the token
        $verificationToken = EmailVerificationToken::verifyToken($token);

        if (!$verificationToken) {
            return redirect()->route('auth.verify.show')
                ->with('error', 'Invalid or expired verification link. Please use the OTP code or request a new verification email.');
        }

        // Get the user
        $user = $verificationToken->user;

        // Check if already verified
        if ($user->email_verified_at) {
            $verificationToken->markAsUsed();
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. You can now log in.');
        }

        // Mark email as verified
        $user->update([
            'email_verified_at' => Carbon::now(),
        ]);

        // Mark token as used
        $verificationToken->markAsUsed();

        return redirect()->route('login')
            ->with('success', 'Your email has been successfully verified! You can now log in.');
    }

    /**
     * Send verification email to a user (used after registration).
     */
    public static function sendVerificationEmail(User $user)
    {
        // Create OTP token
        $token = EmailVerificationToken::createForUser($user->id);

        // Send verification email
        Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));
    }
}
