FROM php:8.2-fpm

# Install required system packages
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Add Composer to the image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install dependencies (ignoring dev tools for security/speed)
RUN composer install --optimize-autoloader --no-dev

# Cache configurations for better performance
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Set proper permissions
RUN chown -R www-data:www-data /var/www