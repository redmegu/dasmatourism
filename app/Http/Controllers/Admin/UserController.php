<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\ActivityLog;
use App\Models\EmailVerificationToken;
use App\Mail\VerificationOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile');

        // Only filter by role if it's provided AND not "all"
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Only filter by status if it's provided AND not "all"
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('is_active', $request->status === 'active');
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->appends($request->all());

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:administrator,user,business_owner',
            'is_active' => 'boolean',
            'auto_verify' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Auto-verify email if admin chooses
        if ($request->has('auto_verify') && $request->auto_verify) {
            $validated['email_verified_at'] = now();
        }

        $user = User::create($validated);

        // Create profile
        UserProfile::create(['user_id' => $user->id]);

        // Send verification email if not auto-verified
        if (!($request->has('auto_verify') && $request->auto_verify)) {
            try {
                // Create OTP token
                $token = EmailVerificationToken::createForUser($user->id);

                // Send verification email
                Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));

                Log::info('Verification email sent to user created by admin', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'created_by' => auth()->id()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email to admin-created user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        ActivityLog::logActivity(
            'create',
            "Created user: {$user->name} ({$user->role})" . ($request->auto_verify ? ' [Auto-verified]' : ' [Verification email sent]'),
            User::class,
            $user->id
        );

        $message = 'User created successfully.';
        if ($request->auto_verify) {
            $message .= ' Email automatically verified.';
        } else {
            $message .= ' A verification email has been sent to ' . $user->email;
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function show(User $user)
    {
        $user->load(['profile', 'reviews', 'business', 'landmarkSuggestions']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:administrator,user,business_owner',
            'is_active' => 'boolean',
        ]);

        // Check if email is being changed
        $emailChanged = $user->email !== $validated['email'];
        $oldEmail = $user->email;

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If email changed, reset verification
        if ($emailChanged) {
            $validated['email_verified_at'] = null;
        }

        $user->update($validated);

        // Send verification email if email was changed
        if ($emailChanged) {
            try {
                // Create OTP token
                $token = EmailVerificationToken::createForUser($user->id);

                // Send verification email to new address
                Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));

                Log::info('Verification email sent after email change by admin', [
                    'user_id' => $user->id,
                    'old_email' => $oldEmail,
                    'new_email' => $user->email,
                    'updated_by' => auth()->id()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email after email change', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        ActivityLog::logActivity(
            'update',
            "Updated user: {$user->name}" . ($emailChanged ? " (email changed from {$oldEmail} to {$user->email})" : ''),
            User::class,
            $user->id
        );

        $message = 'User updated successfully.';
        if ($emailChanged) {
            $message .= ' Email changed - verification email sent to ' . $user->email;
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        ActivityLog::logActivity(
            'delete',
            "Deleted user: {$userName}",
            User::class,
            $user->id
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
