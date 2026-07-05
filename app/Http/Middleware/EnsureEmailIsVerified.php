<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\EmailVerificationToken;
use App\Mail\VerificationOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->email_verified_at) {
            // Create new OTP token
            $token = EmailVerificationToken::createForUser($request->user()->id);

            // Send verification email
            try {
                Mail::to($request->user()->email)->send(new VerificationOtpMail(
                    $request->user(),
                    $token->otp,
                    $token->verification_token
                ));
                Log::info("Verification email sent to unverified user: {$request->user()->email}");
            } catch (\Exception $e) {
                Log::error('Failed to send verification email: ' . $e->getMessage());
            }

            return redirect()->route('auth.verify.show')
                ->with('warning', 'You must verify your email address before accessing this feature. A new verification code has been sent to your email.');
        }

        return $next($request);
    }
}
