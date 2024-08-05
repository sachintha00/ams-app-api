FROM php:8.1.0-fpm-alpine3.15

# Install system dependencies, PHP extensions, and tools like usermod
RUN apk --update add --no-cache \
    shadow \
    sudo \
    npm \
    make \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    imagemagick-dev \
    imagemagick \
    && apk add --no-cache --repository https://dl-cdn.alpinelinux.org/alpine/v3.15/community php8-pecl-imagick \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql \
    && apk del --no-cache make \
    && rm -rf /var/cache/apk/*

# Set the user and group to www-data (ensure shadow package is installed)
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

# Allow www-data to use sudo without a password
RUN echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

# Create necessary directories and set ownership
RUN mkdir -p /var/lib/postgresql/data
RUN chown -R www-data:www-data /var/www/html

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer