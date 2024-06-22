#!/bin/bash
set -e

# Aumentar el tiempo de espera de Composer a 1200 segundos (20 minutos)
export COMPOSER_PROCESS_TIMEOUT=1200

# Copiar .env.example a .env si .env no existe
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
fi

# Generar APP_KEY si no está configurada
if ! grep -q "APP_KEY=base64:" /var/www/.env; then
    php artisan key:generate
fi

# Ejecutar composer install
composer install --no-dev --optimize-autoloader

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Iniciar PHP-FPM
php-fpm
