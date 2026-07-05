<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\EmailVerificationToken;
use App\Models\ActivityLog;
use App\Mail\VerificationOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:user,business_owner'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Create user profile
        UserProfile::create([
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        // Send verification email
        $token = EmailVerificationToken::createForUser($user->id);

        try {
            Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));
            Log::info("Verification email sent successfully to: {$user->email}");
        } catch (\Exception $e) {
            // Log error but don't prevent registration
            Log::error('Failed to send verification email: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            // Token already created, user can resend later
        }

        Auth::login($user);

        // Log new account creation
        ActivityLog::logActivity(
            'register',
            "New account registered: {$user->name} ({$user->email}) as {$user->role}",
            get_class($user),
            $user->id
        );

        // Redirect to email verification page
        return redirect()->route('auth.verify.show')
            ->with('success', 'Registration successful! Please check your email for the verification code.');
    }
}
