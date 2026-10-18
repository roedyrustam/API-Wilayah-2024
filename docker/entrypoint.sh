#!/bin/sh
set -e

# Copy .env.example if .env does not exist
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure storage directories exist and are writable
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/app/exports
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate application key if empty
php artisan key:generate --no-interaction --force || true

# Warm up route and config caches
php artisan config:clear || true
php artisan route:clear || true

exec "$@"
