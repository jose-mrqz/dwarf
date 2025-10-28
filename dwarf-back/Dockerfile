FROM php:8.3-fpm

WORKDIR /app

# Install system deps
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install FrankenPHP
RUN curl https://frankenphp.dev/install.sh | sh \
    && mv frankenphp /usr/local/bin/

# Copy app
COPY . .

# Install PHP deps
RUN composer install --optimize-autoloader --no-dev
RUN php artisan migrate
RUN php artisan storage:link

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

# Serve public/
CMD ["frankenphp", "php-server", "-r", "public/", "--listen", "0.0.0.0:8000"]
