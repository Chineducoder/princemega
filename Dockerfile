FROM dunglas/frankenphp:1-php8.3

ENV SERVER_NAME=":80"
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Install Composer binary from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install required PHP extensions
RUN install-php-extensions \
    pdo_sqlite \
    bcmath \
    pcntl \
    intl \
    zip \
    opcache

WORKDIR /app

# Install Node.js & NPM for Vite asset building
RUN apt-get update && apt-get install -y nodejs npm && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Build assets with Vite
RUN npm install && npm run build && rm -rf node_modules

# Ensure SQLite file and storage directory permissions
RUN mkdir -p database storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

EXPOSE 80

CMD ["sh", "-c", "php artisan migrate --force && php artisan db:seed --force && frankenphp run --config /etc/caddy/Caddyfile"]
