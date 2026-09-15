# Production Dockerfile for Laravel Laundry Application
FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install required system packages and PHP extensions
RUN apk add --no-cache \
    curl \
    git \
    libzip-dev \
    sqlite-dev \
    libxml2-dev \
    oniguruma-dev \
    linux-headers \
    bash \
    && docker-php-ext-install \
    pdo \
    pdo_sqlite \
    bcmath \
    mbstring \
    xml \
    zip \
    opcache

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . /var/www/html

# Configure file permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install dependencies in production mode (without dev dependencies)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy entrypoint script to handle production caching and migrations
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
