# Use an official PHP runtime as a parent image
FROM php:8.3-fpm

# Install Composer 2.0
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.7.2

# Set working directory
WORKDIR /var/www

# Install PHP extensions needed by Laravel
RUN apt-get update && apt-get install -y \
    build-essential \
    libcurl4-openssl-dev \
    libxml2-dev \
    libicu-dev \
    libonig-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev
    
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-install gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp


# Install Xdebug
RUN pecl install xdebug
    
# Copy and add OpCache configuration
COPY config/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
# Copy and add Xdebug configuration
COPY config/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Copy composer files and install dependencies
COPY source/laravel-api/composer.json source/laravel-api/composer.lock ./
# RUN composer install --no-dev --no-scripts --no-autoloader

# Copy laravel-api source
COPY source/laravel-api /var/www

# Set environment variable to allow Composer plugins to run as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Composer dependencies
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Generate optimized autoload files
RUN composer dump-autoload --no-dev --optimize

# Set permissions for Laravel storage and bootstrap cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Run database migrations
# RUN php artisan migrate --force

# Expose port 9000 to communicate with Nginx or other web server
EXPOSE 9000 9001 9003

# Start PHP-FPM
CMD ["php-fpm"]