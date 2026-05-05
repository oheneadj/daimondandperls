#!/bin/bash
# deploy.sh — Run from the project root on the server after git pull
set -e

# ── Set production .env values (non-sensitive only) ───────────────────────────
echo "Configuring environment..."

set_env() {
    local key=$1
    local value=$2
    if grep -q "^${key}=" .env 2>/dev/null; then
        sed -i "s|^${key}=.*|${key}=${value}|" .env
    else
        echo "${key}=${value}" >> .env
    fi
}

set_env APP_ENV                 production
set_env APP_DEBUG               false
set_env APP_URL                 https://diamondsandpearlsgh.com
set_env LOG_LEVEL               warning
set_env SESSION_DRIVER          file
set_env SESSION_SECURE_COOKIE   true
set_env SESSION_DOMAIN          .diamondsandpearlsgh.com
set_env CACHE_STORE             file
set_env QUEUE_CONNECTION        database

# ── Dependencies ──────────────────────────────────────────────────────────────
echo "Installing dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

# ── Frontend assets ───────────────────────────────────────────────────────────
if command -v npm &>/dev/null; then
    echo "Building frontend assets..."
    npm ci --silent && npm run build
else
    echo "npm not found — skipping frontend build (upload public/build manually)."
fi

# ── Laravel optimisation ──────────────────────────────────────────────────────
echo "Caching config, routes, views, events..."
php artisan optimize

# ── Database ──────────────────────────────────────────────────────────────────
echo "Running migrations..."
php artisan migrate --force

# ── Storage symlink ───────────────────────────────────────────────────────────
if [ ! -L public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# ── Sitemap ───────────────────────────────────────────────────────────────────
echo "Regenerating sitemap..."
php artisan sitemap:generate

# ── Queue ─────────────────────────────────────────────────────────────────────
echo "Restarting queue workers..."
php artisan queue:restart

echo ""
echo "Deploy complete!"
