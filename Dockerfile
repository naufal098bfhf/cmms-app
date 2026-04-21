    FROM php:8.2-cli

    # Install system dependencies + Node.js
    RUN apt-get update && apt-get install -y \
        git curl zip unzip libpng-dev libonig-dev libxml2-dev \
        nodejs npm

    # Install PHP extensions
    RUN docker-php-ext-install pdo pdo_mysql

    # Set working directory
    WORKDIR /var/www

    # Copy semua file project
    COPY . .

    # ======================
    # 🔥 INSTALL & BUILD VITE
    # ======================
    RUN npm install
    RUN npm run build

    # ======================
    # INSTALL COMPOSER
    # ======================
    RUN curl -sS https://getcomposer.org/installer | php
    RUN php composer.phar install --no-dev --optimize-autoloader

    # ======================
    # FIX PERMISSION
    # ======================
    RUN mkdir -p storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache \
        bootstrap/cache \
        && chmod -R 777 storage bootstrap/cache

    # ======================
    # CLEAR CACHE
    # ======================
    RUN php artisan config:clear || true
    RUN php artisan cache:clear || true
    RUN php artisan view:clear || true

    # ======================
    # RUN APP
    # ======================
    CMD php artisan serve --host=0.0.0.0 --port=${PORT}
