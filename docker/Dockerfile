# docker/Dockerfile
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear y configurar el directorio de la aplicación
RUN mkdir -p /var/www

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar el código de la aplicación
COPY . /var/www

# Copiar script de entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Establecer permisos
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Instalar dependencias de Composer
# RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 9000 y ejecutar PHP-FPM
EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]
