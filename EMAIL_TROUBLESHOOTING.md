# Email Configuration Troubleshooting Guide

## ❌ Current Issue

**Error**: `Connection could not be established with host "smtp.gmail.com:587": No such host is known`

## 🔍 What This Means

This error indicates that your system cannot reach Gmail's SMTP server. This is typically caused by:

1. **No Internet Connection** - Your development machine is offline
2. **Network/DNS Issues** - Cannot resolve smtp.gmail.com
3. **Firewall Blocking** - Port 587 is blocked
4. **Corporate Network** - Proxy or firewall restrictions

## ✅ Solutions

### Solution 1: Use Log Driver (Recommended for Local Development)

**Best for**: Local development without internet or SMTP access

1. **Update your `.env` file**:

```env
MAIL_MAILER=log
```

2. **How it works**:

    - Emails are written to `storage/logs/laravel.log`
    - You can see the full email content including OTP codes
    - No internet connection needed
    - Perfect for development

3. **Viewing emails**:
    - Open: `storage/logs/laravel.log`
    - Search for the OTP code
    - Copy and use for testing

### Solution 2: Use Mailtrap (Recommended for Team Development)

**Best for**: Testing without sending real emails

1. **Sign up**: https://mailtrap.io (Free plan available)

2. **Update your `.env` file**:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

3. **Benefits**:
    - See emails in web interface
    - Test with teammates
    - No spam risk
    - HTML preview

### Solution 3: Use MailHog (Recommended for Local SMTP Server)

**Best for**: Local SMTP server simulation

1. **Install MailHog**:

    - Download: https://github.com/mailhog/MailHog/releases
    - Or use Docker: `docker run -d -p 1025:1025 -p 8025:8025 mailhog/mailhog`

2. **Update your `.env` file**:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

3. **Access**:
    - Open browser: http://localhost:8025
    - See all sent emails
    - Perfect for development

### Solution 4: Fix Gmail SMTP (For Production)

**Best for**: When you have internet and need real Gmail

1. **Check Internet Connection**:

```bash
ping smtp.gmail.com
```

2. **Verify Gmail Settings**:

    - Enable 2-Factor Authentication
    - Generate App Password: https://myaccount.google.com/apppasswords
    - Use App Password in MAIL_PASSWORD (not regular password)

3. **Update `.env`**:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD="your_app_password_here"
MAIL_ENCRYPTION=tls
```

4. **Test firewall**:

```bash
telnet smtp.gmail.com 587
```

### Solution 5: Use Queue Worker (Current Setup)

**Important**: Since we configured emails to use queues:

1. **Start queue worker** (in a separate terminal):

```bash
php artisan queue:work
```

2. **Or use queue daemon**:

```bash
php artisan queue:listen
```

3. **For development, process queue manually**:

```bash
php artisan queue:work --once
```

## 🧪 Testing the Setup

### Test Command

Run our custom test command:

```bash
php artisan test:email-verification your@email.com
```

This will:

-   ✅ Check SMTP configuration
-   ✅ Test network connectivity
-   ✅ Send a test email
-   ✅ Show detailed error messages

### Manual Test (After Choosing Solution)

1. **Clear config cache**:

```bash
php artisan config:clear
php artisan cache:clear
```

2. **Register a new user** or **use admin to create user**

3. **Check for email**:
    - **Log driver**: Check `storage/logs/laravel.log`
    - **Mailtrap**: Check Mailtrap inbox
    - **MailHog**: Visit http://localhost:8025
    - **Gmail**: Check your inbox

## 🚀 Recommended Setup for Your Case

Since you're getting "No such host is known", you likely have **no internet connection** or **firewall issues**.

### **Quick Fix** (30 seconds):

1. Open `.env` file
2. Change this line:

    ```env
    MAIL_MAILER=smtp
    ```

    To:

    ```env
    MAIL_MAILER=log
    ```

3. Clear cache:

    ```bash
    php artisan config:clear
    ```

4. Test registration - OTP will be in `storage/logs/laravel.log`

### **Find OTP in Logs**:

```bash
# Search for OTP in logs
findstr "OTP" storage\logs\laravel.log

# Or search for email content
findstr "verification" storage\logs\laravel.log
```

## 📊 Comparison

| Solution   | Setup Time | Internet Needed | Best For          |
| ---------- | ---------- | --------------- | ----------------- |
| Log Driver | 30 seconds | ❌ No           | Quick local dev   |
| Mailtrap   | 5 minutes  | ✅ Yes          | Team testing      |
| MailHog    | 10 minutes | ❌ No           | Local dev with UI |
| Gmail SMTP | Variable   | ✅ Yes          | Production        |

## 💡 Next Steps

1. **For immediate testing**: Use Log Driver
2. **For development**: Install MailHog
3. **For production**: Fix Gmail SMTP with App Password
4. **For team**: Use Mailtrap

## 🔧 Additional Commands

### View Queue Jobs

```bash
php artisan queue:work --once
```

### Clear Failed Jobs

```bash
php artisan queue:flush
```

### Monitor Queue

```bash
php artisan queue:listen --verbose
```

### View Logs in Real-time

```bash
Get-Content storage\logs\laravel.log -Wait -Tail 50
```
