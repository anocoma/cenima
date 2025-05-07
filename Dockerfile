FROM php:8.1-fpm-alpine 

# Bật BuildKit Cache
ARG BUILDKIT_INLINE_CACHE=1

# Cài đặt gói alpine cần thiết
RUN apk add --no-cache \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    #libmemcached-dev \
    nginx \
    #nano \
    supervisor \
    mysql-client \
    sed

# Cài đặt composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cài đặt công cụ install-php-extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && sync

# Cài đặt PHP extensions
RUN install-php-extensions mbstring pdo_mysql zip exif pcntl gd opcache #memcached 

# Workdir
WORKDIR /var/www
COPY --chown=www-data:www-data . /var/www

# Cấu hình .env
RUN  cp .env.example .env \
    && sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env \
    && sed -i "s|DB_HOST=.*|DB_HOST=db|" .env \
    && sed -i "s|DB_PORT=.*|DB_PORT=3306|" .env \
    && sed -i "s|DB_DATABASE=.*|DB_DATABASE=datn-hn5|" .env \
    && sed -i "s|DB_USERNAME=.*|DB_USERNAME=andk|" .env \
    && sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=123|" .env \
    && sed -i "s|APP_URL=.*|APP_URL=http://192.168.199.99:8000|" .env

# Build assets
RUN npm install --no-audit --prefer-offline && npm run build  
RUN composer install --optimize-autoloader --no-interaction --no-progress --prefer-dist #--no-dev

# PHP-PFM
RUN mkdir -p /var/run && chown www-data:www-data /var/run && chmod 755 /var/run

# Copy các file cấu hình 
COPY --chown=www-data:www-data docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY --chown=www-data:www-data docker/nginx/default.conf /etc/nginx/sites-enabled/default.conf
COPY --chown=www-data:www-data docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY --chown=www-data:www-data docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=www-data:www-data docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY --chown=www-data:www-data --chmod=755 docker/run.sh /var/www/docker/run.sh

# Tạo thư mục Laravel storage và set quyền
RUN mkdir -p /var/www/storage/logs \
    && mkdir -p /var/www/storage/framework/sessions \
    && mkdir -p /var/www/storage/framework/cache \
    && mkdir -p /var/www/storage/framework/views \
    && chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage

# Tạo logs
RUN mkdir -p /var/log/php /var/log/nginx \
    && touch /var/log/php/errors.log /var/log/php-fpm.log /var/log/nginx.log \
    && chown -R www-data:www-data /var/log/php /var/log/nginx \
    && chmod -R 777 /var/log/php /var/log/nginx

EXPOSE 8080
ENTRYPOINT ["/var/www/docker/run.sh"]

