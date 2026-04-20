FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install

# Fix Laravel storage (INI PENTING)
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    bootstrap/cache \
    && chmod -R 777 storage bootstrap/cache

# Clear cache Laravel
RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan view:clear || true

# Jalankan aplikasi (HARUS PALING BAWAH)
CMD php artisan serve --host=0.0.0.0 --port=${PORT}
