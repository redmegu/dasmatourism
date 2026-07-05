<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\EmailVerificationToken;
use App\Mail\VerificationOtpMail;
use Illuminate\Support\Facades\Mail;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-verification {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email verification system and SMTP connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Email Verification System...');
        $this->newLine();

        // Test 1: Check SMTP Configuration
        $this->info('1. Checking SMTP Configuration:');
        $this->line('   MAIL_MAILER: ' . config('mail.default'));
        $this->line('   MAIL_HOST: ' . config('mail.mailers.smtp.host'));
        $this->line('   MAIL_PORT: ' . config('mail.mailers.smtp.port'));
        $this->line('   MAIL_USERNAME: ' . config('mail.mailers.smtp.username'));
        $this->line('   MAIL_ENCRYPTION: ' . config('mail.mailers.smtp.encryption'));
        $this->line('   MAIL_FROM: ' . config('mail.from.address'));
        $this->newLine();

        // Test 2: Check Network Connectivity
        $this->info('2. Testing Network Connectivity:');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');

        $this->line("   Testing connection to {$host}:{$port}...");

        $connection = @fsockopen($host, $port, $errno, $errstr, 10);
        if ($connection) {
            $this->line('   ✅ Connection successful!');
            fclose($connection);
        } else {
            $this->error("   ❌ Connection failed: {$errstr} (Error: {$errno})");
            $this->newLine();
            $this->warn('⚠️  Possible issues:');
            $this->line('   - No internet connection');
            $this->line('   - Firewall blocking port ' . $port);
            $this->line('   - DNS resolution failure');
            $this->line('   - SMTP server is down');
            $this->newLine();
            $this->info('💡 For local development, consider using:');
            $this->line('   - MailHog (local SMTP server)');
            $this->line('   - Mailtrap.io (email testing service)');
            $this->line('   - Log driver (MAIL_MAILER=log in .env)');
            return 1;
        }
        $this->newLine();

        // Test 3: Test Email Sending
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Enter an email address to test (or press Enter to skip)');
        }

        if ($email) {
            $this->info('3. Sending Test Email:');

            // Find or create test user
            $user = User::where('email', $email)->first();

            if (!$user) {
                $this->warn("   User not found with email: {$email}");
                if ($this->confirm('Create a test user?', true)) {
                    $user = User::create([
                        'name' => 'Test User',
                        'email' => $email,
                        'password' => bcrypt('password'),
                        'role' => 'user',
                    ]);
                    $this->info("   ✅ Test user created!");
                } else {
                    return 0;
                }
            }

            try {
                $token = EmailVerificationToken::createForUser($user->id);
                $this->line("   Generated OTP: {$token->otp}");
                $this->line("   Sending email to: {$email}");

                Mail::to($email)->send(new VerificationOtpMail($user, $token->otp, $token->verification_token));

                $this->newLine();
                $this->info('✅ Email sent successfully!');
                $this->line("   OTP Code: {$token->otp}");
                $this->line("   Expires at: {$token->expires_at}");
            } catch (\Exception $e) {
                $this->newLine();
                $this->error('❌ Failed to send email!');
                $this->error('   Error: ' . $e->getMessage());
                $this->newLine();

                // Provide detailed error information
                if (str_contains($e->getMessage(), 'Connection could not be established')) {
                    $this->warn('⚠️  SMTP Connection Issue:');
                    $this->line('   This usually means:');
                    $this->line('   1. No internet connection available');
                    $this->line('   2. Firewall is blocking SMTP port');
                    $this->line('   3. Gmail requires "Less secure app access" enabled');
                    $this->line('   4. Gmail App Password is invalid');
                    $this->newLine();
                    $this->info('💡 Quick Fix for Development:');
                    $this->line('   Switch to log driver in .env:');
                    $this->line('   MAIL_MAILER=log');
                    $this->line('   Then check emails in: storage/logs/laravel.log');
                }

                return 1;
            }
        }

        $this->newLine();
        $this->info('🎉 Email verification system test completed!');

        return 0;
    }
}
