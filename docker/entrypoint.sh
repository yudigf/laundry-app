#!/bin/sh
set -e

# Ensure .env file is present
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate APP_KEY if missing
if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Ensure SQLite database exists
mkdir -p database
touch database/database.sqlite
chown -R www-data:www-data database

# Run database migrations
php artisan migrate --force

# Production caching optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
