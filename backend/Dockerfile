# Hospital System API — production image (PHP 8.3 + Apache)
FROM php:8.3-apache

# System packages + PHP extensions Laravel and this app need
RUN apt-get update && apt-get install -y --no-install-recommends \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Serve Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/docker-php.conf \
    && echo 'ServerName localhost' >> /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

# Apache vhost: DocumentRoot public, .htaccess enabled, /storage alias
COPY docker/hospital.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Install PHP dependencies first (cached unless the lockfile changes)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Application source (backend/.dockerignore keeps .env and local files out)
COPY . .

# Runtime permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x docker/start.sh

EXPOSE 80

CMD ["docker/start.sh"]
