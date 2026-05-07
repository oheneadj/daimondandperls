# Diamonds & Pearls — Production Deployment Checklist

> Work through this top to bottom. Every item marked ✅ is done in code and will be deployed automatically. Items marked 🔧 require action on the server or third-party service.

---

## Phase 1 — Code (Already Done ✅)

These are baked into the codebase and deploy automatically.

| # | Item | Status |
|---|------|--------|
| 1 | SEO meta tags, Open Graph, Twitter Card on all public pages | ✅ |
| 2 | JSON-LD `FoodEstablishment` structured data | ✅ |
| 3 | Google Analytics script (production-only) | ✅ |
| 4 | Font preload hint | ✅ |
| 5 | `robots.txt` — blocks admin/dashboard, adds Sitemap directive | ✅ |
| 6 | `sitemap:generate` command implemented with categories | ✅ |
| 7 | Sitemap scheduled daily via Laravel Scheduler | ✅ |
| 8 | Vite `manualChunks` — chart.js + apexcharts split into separate bundles | ✅ |
| 9 | `SecurityHeaders` middleware — X-Frame-Options, X-Content-Type-Options, Referrer-Policy | ✅ |
| 10 | `.htaccess` — HTTPS redirect, Gzip compression, 1-year asset cache headers | ✅ |
| 11 | Sentry installed (`sentry/sentry-laravel`) + `Integration::handles()` in `bootstrap/app.php` | ✅ |
| 12 | `Queue::failing()` broad alert → `Log::critical` in `AppServiceProvider` | ✅ |
| 13 | `deploy.sh` — sets env vars, runs migrations, sitemap, queue restart | ✅ |
| 14 | Storage symlink created automatically in `deploy.sh` | ✅ |
| 15 | Production readiness checklist in Admin → Settings → System | ✅ |
| 16 | Uptime monitoring URL + Sentry project URL saved in Settings → System | ✅ |

---

## Phase 2 — Server Setup 🔧

Do these on your production server via SSH.

### 2a. First Deploy

```bash
# SSH into your server
ssh user@diamondsandpearlsgh.com

# Navigate to project root
cd /path/to/dpc

# Pull latest code
git pull origin main

# Run the deploy script (handles env, composer, migrations, sitemap, etc.)
bash deploy.sh
```

### 2b. Environment Variables

The `.env` on the server must have all of these set. `deploy.sh` handles the non-sensitive ones automatically. You must set these **manually** (they contain secrets):

```env
# Application
APP_KEY=base64:your-key-here          # php artisan key:generate if missing

# Database (create DB in cPanel first)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Payment Gateways
TRANSFLOW_PUBLIC_KEY=your-key
TRANSFLOW_SECRET_KEY=your-key
MOOLRE_API_KEY=your-key
MOOLRE_SECRET=your-key

# Email (Brevo)
BREVO_API_KEY=your-key
MAIL_FROM_ADDRESS=info@diamondsandpearlsgh.com
MAIL_FROM_NAME="Diamonds and Pearls Catering"

# SMS (GaintSMS)
GAINTSMS_API_TOKEN=your-token
GAINTSMS_SENDER_ID=DPC

# Error Tracking
SENTRY_LARAVEL_DSN=https://your-dsn@sentry.io/your-project

# Analytics
GOOGLE_ANALYTICS_ID=G-XR55KX5FK5
```

> ⚠️ Never commit `.env` to git. It is in `.gitignore`.

### 2c. Database

1. Log into **cPanel → MySQL Databases**
2. Create a new database and user
3. Grant the user **all privileges** on the database
4. Add the credentials to `.env` on the server
5. Run migrations (handled by `deploy.sh`):
   ```bash
   php artisan migrate --force
   ```

### 2d. Laravel Scheduler (Cron)

Add this in **cPanel → Cron Jobs**:

```
* * * * * cd /path/to/dpc && php artisan schedule:run >> /dev/null 2>&1
```

This powers:
- `booking:cleanup-abandoned` — runs hourly
- `logs:purge` — runs at 2am daily
- `sitemap:generate` — runs daily

### 2e. PHP OPcache

Ask your host to enable OPcache, or add to `php.ini`:

```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.validate_timestamps=0
```

> After each deploy, restart PHP-FPM or contact your host to clear OPcache.

---

## Phase 3 — Third-Party Services 🔧

### 3a. Sentry (Error Tracking)

1. Sign up at [sentry.io](https://sentry.io) — free tier (5k errors/month)
2. Create a **Laravel** project
3. Copy the DSN → add to prod `.env` as `SENTRY_LARAVEL_DSN`
4. Test: `php artisan sentry:test`
5. Copy your Sentry Issues URL → save in **Admin → Settings → System → Error Tracking**

### 3b. UptimeRobot (Uptime Monitoring)

1. Sign up at [uptimerobot.com](https://uptimerobot.com) — free tier
2. Add New Monitor:
   - Type: **HTTP(s)**
   - Name: `Diamonds & Pearls – Production`
   - URL: `https://diamondsandpearlsgh.com/up`
   - Interval: **5 minutes**
3. Add your email as an alert contact
4. Copy your status page URL → save in **Admin → Settings → System → Uptime Monitoring**

### 3c. Google Search Console

1. Go to [search.google.com/search-console](https://search.google.com/search-console)
2. Add property: `https://diamondsandpearlsgh.com`
3. Verify via DNS TXT record (your host's control panel)
4. Submit sitemap: `https://diamondsandpearlsgh.com/sitemap.xml`
5. Check back after 48 hours for indexing status

---

## Phase 4 — Post-Launch Testing 🔧

Run these manually on the live site after DNS is live.

| # | Test | What to verify |
|---|------|----------------|
| 1 | Place a test booking (meal) | Full checkout flow completes, confirmation email + SMS received |
| 2 | Place a test event inquiry | Inquiry received, admin notified |
| 3 | Make a real payment (small amount) via Transflow | Webhook fires, booking status updates to paid |
| 4 | Make a real payment via Moolre | Same as above |
| 5 | Register a new customer account | OTP SMS arrives within 60 seconds |
| 6 | Download a PDF invoice | Renders correctly with logo and booking details |
| 7 | Submit a review | Review appears in Admin → Reviews |
| 8 | Log in as admin | All sections accessible, no 403 errors |
| 9 | Check `failed_jobs` table is empty | `php artisan tinker` → `DB::table('failed_jobs')->count()` |
| 10 | Run Google PageSpeed Insights | Target score 90+ on mobile and desktop |
| 11 | Check Sentry dashboard | No unexpected errors after test booking |
| 12 | Check UptimeRobot | Monitor showing green / up |

---

## Phase 5 — Ongoing Maintenance

| Task | Frequency | How |
|------|-----------|-----|
| Deploy new code | As needed | `git pull && bash deploy.sh` |
| Check `failed_jobs` | Daily (first week) | Admin → System Logs → Notifications tab |
| Check Sentry for new errors | Weekly | Admin → Settings → System → Open Sentry |
| Check UptimeRobot status | On alert | Admin → Settings → System → View Status |
| Backup database | Daily | Ask host for automated backups or use `mysqldump` |
| Renew SSL certificate | Yearly | Most hosts auto-renew via Let's Encrypt |
| Review Google Search Console | Monthly | Check for crawl errors and indexing issues |

---

## Quick Reference — Useful Commands

```bash
# Deploy
bash deploy.sh

# Clear all caches
php artisan optimize:clear

# Re-cache everything
php artisan optimize

# Generate sitemap manually
php artisan sitemap:generate

# Test Sentry
php artisan sentry:test

# Check queue
php artisan queue:monitor

# Check failed jobs
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all

# Put site in maintenance mode
php artisan down

# Bring site back up
php artisan up
```

---

## Production Readiness Scoreboard

> Check this live in **Admin → Settings → System → Production Readiness**.
> All 22 checks should be green before going live.

| Group | Checks |
|-------|--------|
| Environment | APP_ENV, APP_DEBUG, HTTPS URL, Log level |
| Database & Sessions | MySQL driver, session driver, cache driver, secure cookie |
| Queue | Queue driver, no failed jobs |
| Server | OPcache, storage symlink |
| Security | Security headers middleware |
| SEO & Marketing | Sitemap, Google Analytics |
| Monitoring | Sentry DSN, Sentry URL saved, UptimeRobot URL saved |
| Business Setup | Business name, mail from address, delivery locations |
