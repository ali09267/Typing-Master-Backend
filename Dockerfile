# Use PHP 8.3 CLI
FROM php:8.3-cli

# Set working directory
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip curl libzip-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set correct permissions for Laravel storage and cache
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Ensure PORT is used if provided, fallback to 8000 for local testing
ENV PORT 8000

# Start PHP built-in server on the correct port
CMD sh -c "php -S 0.0.0.0:\$PORT -t public"
