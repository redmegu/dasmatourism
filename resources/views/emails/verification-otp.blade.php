<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: 600;
            margin: 0;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 20px;
            color: #333333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .otp-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .otp-label {
            color: #ffffff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .otp-code {
            font-size: 42px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .expiry-notice {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .expiry-notice p {
            color: #856404;
            font-size: 14px;
            margin: 0;
        }

        .instructions {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .instructions h3 {
            color: #333333;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .instructions ol {
            margin-left: 20px;
            color: #555555;
        }

        .instructions li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            color: #777777;
            font-size: 13px;
            line-height: 1.6;
            margin: 5px 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e0e0e0, transparent);
            margin: 25px 0;
        }

        .security-notice {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }

        .security-notice p {
            color: #0c5460;
            font-size: 14px;
            margin: 0;
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .verify-button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: transform 0.2s ease;
        }

        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }

            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }

            .greeting {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header with Logo -->
        <div class="header">
            <div class="logo">
                <!-- Dasmariñas Tourism Logo -->
                <img src="{{ config('app.url') }}/assets/dasma-logo.png" alt="Dasmariñas Tourism Logo">
            </div>
            <h1>Email Verification</h1>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Hello, {{ $user->name }}!
            </div>

            <div class="message">
                Thank you for registering with <strong>Dasmariñas Tourism System</strong>. To complete your registration
                and activate your account, please verify your email address using the One-Time Password (OTP) below.
            </div>

            <!-- Quick Verify Button -->
            <div class="button-container">
                <a href="{{ config('app.url') }}/verify-email/{{ $verificationToken }}" class="verify-button">
                    ✅ Verify Email Now (One-Click)
                </a>
            </div>

            <div class="divider"></div>

            <div class="message" style="text-align: center; margin: 20px 0;">
                <strong>Or use this verification code manually:</strong>
            </div>

            <!-- OTP Display -->
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <!-- Verify Button -->
            <div class="button-container">
                <a href="{{ config('app.url') }}/verify-email?email={{ urlencode($user->email) }}" class="verify-button"
                    style="background: linear-gradient(135deg, #52c234 0%, #1a7838 100%);">
                    📝 Enter Code Manually
                </a>
            </div>

            <!-- Expiry Notice -->
            <div class="expiry-notice">
                <p><strong>⏰ Important:</strong> This verification code will expire in <strong>15 minutes</strong>.
                    Please use it as soon as possible.</p>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>Two easy ways to verify your email:</h3>
                <ol>
                    <li><strong>Quick Method:</strong> Click the "Verify Email Now" button at the top - instant
                        verification!</li>
                    <li><strong>Manual Method:</strong> Click "Enter Code Manually" and type the 6-digit code shown
                        above</li>
                </ol>
                <p style="margin-top: 15px; color: #666; font-size: 13px;">
                    💡 <strong>Tip:</strong> The one-click verification link is faster and easier!
                </p>
            </div>

            <div class="divider"></div>

            <!-- Security Notice -->
            <div class="security-notice">
                <p><strong>🔒 Security Notice:</strong> If you didn't create an account with Dasmariñas Tourism System,
                    please ignore this email. Your email address will not be used without verification.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Dasmariñas Tourism System</strong></p>
            <p>Discover the beauty and heritage of Dasmariñas, Cavite</p>
            <p style="margin-top: 15px;">
                Need help? Contact us at <a
                    href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
            </p>
            <p style="margin-top: 10px; color: #999999; font-size: 12px;">
                © {{ date('Y') }} Dasmariñas Tourism. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
