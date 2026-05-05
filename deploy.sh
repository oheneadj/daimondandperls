#!/bin/bash
# deploy.sh — Run from the project root on the server after git pull
set -e

echo "Installing dependencies..."
composer install --optimize-autoloader --no-dev --no-interaction

echo "Building frontend assets..."
npm ci --silent && npm run build

echo "Caching config, routes, views, events..."
php artisan optimize

echo "Running migrations..."
php artisan migrate --force

echo "Regenerating sitemap..."
php artisan sitemap:generate

echo "Restarting queue workers..."
php artisan queue:restart

echo "Deploy complete!"
