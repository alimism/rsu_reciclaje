#!/bin/bash
set -e

# Run composer install
composer install --no-dev --optimize-autoloader

# Run migrations and seeders
php artisan migrate --seed

# Then start PHP-FPM
php-fpm
