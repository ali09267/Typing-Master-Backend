# Use PHP 8.3 CLI image
FROM php:8.3-cli

# Set working directory inside container
WORKDIR /app

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy Laravel project files
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose the port Laravel will run on
EXPOSE 8080

# Run Laravel using PHP's built-in server
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
