# ==============================================================================
# API-Wilayah-2026 Production Dockerfile
# High-performance PHP 8.2-FPM + Nginx + OPcache + SQLite/MySQL Drivers
# ==============================================================================

FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & build tools
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    sqlite-dev \
    postgresql-dev \
    bash \
    git \
    unzip

# Install PHP extensions required for Laravel 10+ and APIs
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        pdo_pgsql \
        mbstring \
        zip \
        bcmath \
        opcache \
        intl \
        gd

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy custom Nginx, Supervisord, and OPcache configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy application files
COPY . /var/www/html

# Install composer production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose HTTP port
EXPOSE 80

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/api/v1/status || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
