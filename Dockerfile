FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

# System dependencies
RUN apt-get update && apt-get install -y \
    software-properties-common \
    ca-certificates \
    curl \
    openssl \
    vim \
    unzip \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update

# PHP 8.5.2
RUN apt-get install -y \
    php8.5 \
    php8.5-cli \
    php8.5-fpm \
    php8.5-mysql \
    php8.5-gd \
    php8.5-xml \
    php8.5-mbstring \
    php8.5-curl \
    php8.5-zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php \
    -- --install-dir=/usr/local/bin --filename=composer

# PHP config (CLI)
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/8.5/cli/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/8.5/cli/php.ini

# PHP config (FPM)
RUN sed -i 's/upload_max_filesize = .*/upload_max_filesize = 1000M/' /etc/php/8.5/fpm/php.ini \
 && sed -i 's/post_max_size = .*/post_max_size = 1000M/' /etc/php/8.5/fpm/php.ini

# App directory
WORKDIR /var/www

# Copy source
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissions (Laravel)
RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
