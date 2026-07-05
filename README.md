# Dasmariñas Tourism Web System

A Laravel-based tourism management web application for Dasmariñas, Cavite. Provides a comprehensive platform for exploring local attractions, businesses, and events — with role-based dashboards for administrators, business owners, and regular users.

## Features

- **Public** — Browse attractions, businesses, and announcements with an interactive custom map
- **Hero carousel** — Admin-managed rotating image banner on the home page
- **Story mode** — Branching narrative exploration of the city
- **User accounts** — Registration with OTP email verification, bookmarks, reviews, and suggestions
- **Business owner dashboard** — Manage listings, images, and promotions
- **Admin dashboard** — Full management of attractions, businesses, users, categories, announcements, hero slides, activity logs, and reports

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade, Bootstrap 5, Boxicons, Vite 7
- **Database:** MySQL
- **Auth:** Laravel session auth with role-based middleware

---

## Default Login Accounts (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Administrator | `admin@dasmarinas.gov.ph` | `password` |
| Business Owner | `juan@business.com` | `password` |
| Business Owner | `maria@business.com` | `password` |
| Regular User | `user1@example.com` | `password` |

---

## Local Development Setup

### Requirements

| Tool | Minimum Version | Download |
|------|----------------|---------|
| PHP | 8.2+ | Via [Laragon](https://laragon.org/download/) (Windows) or `brew install php` (Mac) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org/download/) |
| Node.js | 18+ | [nodejs.org](https://nodejs.org/) |
| MySQL | 8.x | Included in Laragon, or `brew install mysql` |

> **Windows users:** [Laragon Full](https://laragon.org/download/) is the easiest option — it installs PHP, MySQL, and a local server in one go. When installing Composer on Windows, point it to `C:\laragon\bin\php\php-8.x.x\php.exe`.

---

### Step 1 — Get the project

```bash
git clone https://github.com/redmegu/dasmatourism.git
cd your-repo
```

Or extract the ZIP and open a terminal inside the project folder.

---

### Step 2 — Install dependencies

```bash
composer install
npm install
```

> **Composer error: "zip extension missing"** — Open `C:\laragon\bin\php\php-8.x.x\php.ini` in Notepad, find `;extension=zip` and remove the semicolon. Save and retry.

---

### Step 3 — Set up environment

```bash
cp .env.example .env
```

Open `.env` in a text editor and update these values:

```env
APP_NAME="Dasmarinas Tourism"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dasmarinas_tourism
DB_USERNAME=root
DB_PASSWORD=
```

Leave `DB_PASSWORD=` blank if you haven't set a MySQL root password (default for Laragon).

---

### Step 4 — Generate app key

```bash
php artisan key:generate
```

---

### Step 5 — Create the database

```bash
mysql -u root -p
```

Inside the MySQL prompt:

```sql
CREATE DATABASE dasmarinas_tourism CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

> **Laragon users:** You can also use phpMyAdmin (Menu → phpMyAdmin) to create the database through a UI.

---

### Step 6 — Run migrations and seed

```bash
php artisan migrate --seed
```

This creates all tables and populates them with sample data. Type `yes` if prompted.

---

### Step 7 — Link storage

```bash
php artisan storage:link
```

Makes uploaded images accessible from the browser.

---

### Step 8 — Build frontend assets

```bash
npm run build
```

> While actively developing, use `npm run dev` instead for hot-reload.

---

### Step 9 — Start the server

```bash
php artisan serve
```

Visit **http://localhost:8000** — the site should be running.

---

### All commands in order

```bash
composer install
npm install
cp .env.example .env
# edit .env with your DB credentials
php artisan key:generate
# create the MySQL database first
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

---

### Common Local Errors

| Error | Fix |
|-------|-----|
| `zip extension missing` | Enable `extension=zip` in php.ini |
| `Column already exists: email_verified_at` | Run `php artisan migrate:fresh --seed` |
| Pages look unstyled | Run `npm run build` |
| `No application encryption key` | Run `php artisan key:generate` |
| Database connection refused | Check MySQL is running; check DB_* values in .env |
| Images not showing | Run `php artisan storage:link` |
| Cache issues after editing .env | Run `php artisan config:clear` |

---

### Email / OTP Locally

By default, `.env.example` sets `MAIL_MAILER=log` — emails are written to `storage/logs/laravel.log` instead of sent. Find OTP codes there during local testing.

For real email, update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Dasmarinas Tourism"
```

> Gmail requires an **App Password** — generate one at: Google Account → Security → 2-Step Verification → App Passwords.

---

## Deploying to Hostinger

### Requirements

Hostinger's Business or Cloud hosting plan with:
- PHP 8.2+ (set in hPanel)
- MySQL database
- SSH access (recommended)

---

### Step 1 — Create a MySQL database in hPanel

1. Log in to [hPanel](https://hpanel.hostinger.com)
2. Go to **Databases → MySQL Databases**
3. Create a new database — note the **database name**, **username**, and **password**

---

### Step 2 — Set PHP version to 8.2+

1. In hPanel go to **Advanced → PHP Configuration**
2. Select **PHP 8.2** or higher
3. Make sure these extensions are enabled: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, `zip`

---

### Step 3 — Upload the project files

**Option A — Via Git (recommended if SSH is available):**

1. In hPanel go to **Advanced → SSH Access** and enable it
2. Connect via SSH:
   ```bash
   ssh your-username@your-server-ip
   ```
3. Navigate to public_html and clone:
   ```bash
   cd ~/
   git clone https://github.com/redmegu/dasmatourism.git dasmarinas
   ```

**Option B — Via File Manager:**

1. Zip your project folder (excluding `vendor/` and `node_modules/`)
2. In hPanel go to **Files → File Manager**
3. Upload the zip to `public_html/`
4. Extract it there

---

### Step 4 — Point the domain to the Laravel public folder

Laravel's entry point is the `public/` folder, not the project root. In hPanel:

1. Go to **Websites → your domain → Manage**
2. Go to **Advanced → PHP Configuration** or **File Manager**
3. Set the webroot / document root to `public_html/your-project/public`

> If you can't change the document root, an alternative is to move the contents of `public/` into `public_html/` and update `public_html/index.php` to point to the Laravel core:
> ```php
> require __DIR__.'/../dasmarinas/vendor/autoload.php';
> $app = require_once __DIR__.'/../dasmarinas/bootstrap/app.php';
> ```

---

### Step 5 — Install dependencies via SSH

```bash
cd ~/dasmarinas
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

> If `npm` is not available on the host, build locally first (`npm run build`) and upload the `public/build/` folder manually via File Manager.

---

### Step 6 — Configure the environment

```bash
cp .env.example .env
nano .env
```

Update for production:

```env
APP_NAME="Dasmarinas Tourism"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_hostinger_db_name
DB_USERNAME=your_hostinger_db_user
DB_PASSWORD=your_hostinger_db_password

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Dasmarinas Tourism"
```

> `APP_DEBUG=false` is important — never leave it `true` in production as it exposes error details.

---

### Step 7 — Generate app key

```bash
php artisan key:generate
```

---

### Step 8 — Run migrations

```bash
php artisan migrate --seed
```

Type `yes` when prompted. This creates all tables and seeds sample data.

---

### Step 9 — Link storage

```bash
php artisan storage:link
```

This creates `public/storage` → `storage/app/public` symlink so uploaded images are accessible.

---

### Step 10 — Set folder permissions

```bash
chmod -R 775 storage bootstrap/cache
```

---

### Step 11 — Optimize for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### Step 12 — Verify

Visit your domain — the site should load. Test logging in with the admin account and uploading an image to confirm storage is working.

---

### Hostinger-specific notes

- **File manager uploads** — If uploading without SSH, build assets locally (`npm run build`) and upload the `public/build/` folder via File Manager
- **Clearing cache after changes** — After any code update run: `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- **After pulling new changes from Git:**
  ```bash
  git pull
  composer install --optimize-autoloader --no-dev
  php artisan migrate
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
- **Storage not persisting between deployments** — Uploaded files live in `storage/app/public/`. Back this up before redeploying.

---

## Documentation

- `EMAIL_VERIFICATION_GUIDE.md` — OTP email setup
- `EMAIL_TROUBLESHOOTING.md` — Common email issues
- `CHARACTER_MODELS_FEATURE.md` — Story mode character system
- `documents/privacy_policy.md` — Privacy policy
- `documents/terms_of_use.md` — Terms of use
