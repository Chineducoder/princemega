FROM php:8.4-apache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Configure Apache DocumentRoot to Laravel's public directory and enable mod_rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# Install Composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    libpng-dev \
    libicu-dev \
    nodejs \
    npm \
    && docker-php-ext-install \
    pdo_sqlite \
    bcmath \
    pcntl \
    intl \
    zip \
    opcache \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application files
COPY . .

# Setup necessary directories with proper permissions
RUN mkdir -p database \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    storage/logs \
    bootstrap/cache \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 777 storage bootstrap/cache database

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

# Build assets with Vite
RUN npm install && npm run build && rm -rf node_modules

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]
