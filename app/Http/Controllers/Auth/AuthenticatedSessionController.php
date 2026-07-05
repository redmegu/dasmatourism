<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use App\Models\ActivityLog;
use App\Models\EmailVerificationToken;
use App\Mail\VerificationOtpMail;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on role
        $user = Auth::user();

        // Check if email is verified
        if (!$user->email_verified_at) {
            // Generate a new OTP for the unverified user
            $token = EmailVerificationToken::createForUser($user->id);

            // Send verification email
            try {
                Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));
                Log::info("Verification email sent to unverified user attempting login: {$user->email}");
            } catch (\Exception $e) {
                Log::error('Failed to send verification email on login: ' . $e->getMessage());
            }

            // Don't logout immediately - redirect to verification page
            return redirect()->route('auth.verify.show')
                ->with('warning', 'Please verify your email address to continue. A new verification code has been sent to your email.');
        }

        if ($user->role === 'administrator') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif ($user->role === 'business_owner') {
            return redirect()->intended(route('business-owner.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
