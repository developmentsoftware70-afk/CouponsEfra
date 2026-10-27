FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install composer dependencies without running scripts during image build
RUN cd /var/www/html/application && composer install --no-dev --no-scripts --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/application/storage /var/www/html/application/bootstrap/cache \
    && chmod -R 775 /var/www/html/application/storage /var/www/html/application/bootstrap/cache

# Allow .htaccess overrides in Apache
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

EXPOSE 80

CMD ["/bin/sh", "-c", "cd /var/www/html/application && php artisan package:discover --ansi && apache2-foreground"]
