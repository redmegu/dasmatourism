<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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

        .button-container {
            text-align: center;
            margin: 35px 0;
        }

        .reset-button {
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

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
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

        .alternative-link {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }

        .alternative-link p {
            color: #555555;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .alternative-link a {
            color: #667eea;
            word-break: break-all;
            font-size: 12px;
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

        @media only screen and (max-width: 600px) {
            .content {
                padding: 30px 20px;
            }

            .reset-button {
                padding: 14px 30px;
                font-size: 15px;
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
            <h1>Password Reset Request</h1>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Hello!
            </div>

            <div class="message">
                You are receiving this email because we received a password reset request for your account
                (<strong>{{ $userEmail }}</strong>) on the <strong>Dasmariñas Tourism System</strong>.
            </div>

            <div class="message">
                To reset your password, please click the button below:
            </div>

            <!-- Reset Button -->
            <div class="button-container">
                <a href="{{ $resetUrl }}" class="reset-button">
                    🔐 Reset Password
                </a>
            </div>

            <!-- Expiry Notice -->
            <div class="expiry-notice">
                <p><strong>⏰ Important:</strong> This password reset link will expire in <strong>60 minutes</strong>.
                    Please use it as soon as possible.</p>
            </div>

            <!-- Alternative Link -->
            <div class="alternative-link">
                <p><strong>Button not working?</strong> Copy and paste this link into your browser:</p>
                <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
            </div>

            <div class="divider"></div>

            <!-- Security Notice -->
            <div class="security-notice">
                <p><strong>🔒 Security Notice:</strong> If you did not request a password reset, no further action is
                    required. Your password will remain unchanged, and your account is secure.</p>
            </div>

            <div class="message" style="margin-top: 25px; font-size: 14px;">
                <strong>Why am I receiving this email?</strong><br>
                This email was sent because someone (hopefully you) requested a password reset for your account. If this
                wasn't you, please ignore this email or contact our support team if you have concerns.
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
