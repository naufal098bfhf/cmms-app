FROM php:8.2-cli

# ======================
# INSTALL DEPENDENCIES
# ======================
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev \
    nodejs npm

# ======================
# INSTALL PHP EXTENSIONS
# ======================
RUN docker-php-ext-install pdo pdo_mysql

# ======================
# SET WORKDIR
# ======================
WORKDIR /var/www

# ======================
# COPY PROJECT
# ======================
COPY . .

# ======================
# INSTALL NODE + BUILD VITE
# ======================
RUN npm install
RUN npm run build

# ======================
# INSTALL COMPOSER
# ======================
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --no-dev --optimize-autoloader

# ======================
# PERMISSION
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
# 🔥 AUTO MIGRATE + RUN
# ======================
CMD php artisan migrate:fresh --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=${PORT}
