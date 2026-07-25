# =================================================================
# URSAC Hub — Laravel demo image
# Runs Nginx + PHP-FPM + a SQLite database, all in one container.
# No external DB service, no paid add-ons.
# =================================================================

# ---- Stage 1: build frontend assets (Vite/Laravel Mix) ----------
# Skip/delete this stage if your app has no npm build step
# (e.g. plain Blade + CDN CSS, no resources/js compiled).
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.* ./
COPY public ./public
RUN npm run build

# ---- Stage 2: PHP application ------------------------------------
# richarvey/nginx-php-fpm ships PHP 8.2.7 + Nginx on Alpine.
# If your composer.json requires PHP 8.3+, swap the base image for
# tangramor/nginx-php8-fpm:php8.3-latest (also bundles Node, so you
# could drop Stage 1 too) — check your composer.json "require.php"
# constraint before deploying.
FROM richarvey/nginx-php-fpm:3.1.6

COPY . .
COPY --from=assets /app/public/build /var/www/html/public/build

# --- Image / server config ---
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV COMPOSER_ALLOW_SUPERUSER=1

# --- Laravel runtime config ---
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV CACHE_DRIVER=file
ENV SESSION_DRIVER=file
ENV QUEUE_CONNECTION=sync

# --- Database: SQLite file, no separate DB server required ---
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/html/database/database.sqlite

# Make sure the sqlite file + writable dirs exist at build time
RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chmod -R 775 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]
