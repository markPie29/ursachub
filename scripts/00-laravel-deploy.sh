#!/usr/bin/env bash
set -e

echo "==> [1/8] Installing composer dependencies"
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

echo "==> [2/8] Ensuring .env exists"
if [ ! -f /var/www/html/.env ]; then
  cp /var/www/html/.env.example /var/www/html/.env
fi

echo "==> [3/8] Generating app key (safe to re-run)"
php artisan key:generate --force

echo "==> [4/8] Ensuring SQLite database file exists"
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

echo "==> [5/8] Linking storage & media"
if [ -d "/var/www/html/ursachub db" ]; then
  cp -r "/var/www/html/ursachub db/gcash_proofs" /var/www/html/storage/app/public/ 2>/dev/null || true
  cp -r "/var/www/html/ursachub db/logos" /var/www/html/storage/app/public/ 2>/dev/null || true
  cp -r "/var/www/html/ursachub db/news_photos" /var/www/html/storage/app/public/ 2>/dev/null || true
  cp -r "/var/www/html/ursachub db/product_photos" /var/www/html/storage/app/public/ 2>/dev/null || true
fi
php artisan storage:link || true

echo "==> [6/8] Resetting demo database (fresh migrate + seed)"
# NOTE: this wipes and reseeds the DB on every boot/restart/redeploy.
# That's intentional for a public demo (always a clean, working state),
# but it means demo visitors' data does NOT persist. See checklist.
php artisan migrate:fresh --seed --force

echo "==> [7/8] Caching config, routes, views"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> [8/8] Deploy script complete"
