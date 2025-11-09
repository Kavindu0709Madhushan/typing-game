#!/usr/bin/env bash
# Tell Render this is a PHP project
echo "🚀 Building Laravel app on Render (PHP)..."

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Create symbolic links for storage
php artisan storage:link

# Run migrations if any
php artisan migrate --force || true
