<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Mail\VerificationOtpMail;
use Illuminate\Support\Facades\Mail;

class SendTestVerificationEmail extends Command
{
    protected $signature = 'email:send-test-verification {email}';
    protected $description = 'Send a test verification email to specified address';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("📧 Sending test verification email to: {$email}");

        // Find or create user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ User not found with email: {$email}");

            if (!$this->confirm('Create a test user?')) {
                return 1;
            }

            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'password' => bcrypt('password123'),
                'role' => 'user',
            ]);

            $this->info("✅ Test user created");
        }

        try {
            // Create OTP token
            $token = EmailVerificationToken::createForUser($user->id);
            $this->line("🔑 Generated OTP: {$token->otp}");

            // Send email
            $this->line("📮 Sending email...");
            Mail::to($user->email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));

            $this->newLine();
            $this->info("✅ SUCCESS! Email sent to {$email}");
            $this->line("📧 Check your inbox for the verification email");
            $this->line("🔑 OTP Code: {$token->otp}");
            $this->line("⏰ Valid until: {$token->expires_at}");

            return 0;
        } catch (\Exception $e) {
            $this->newLine();
            $this->error("❌ FAILED to send email!");
            $this->error("Error: " . $e->getMessage());
            $this->newLine();
            $this->warn("💡 Error details logged to storage/logs/laravel.log");

            return 1;
        }
    }
}
