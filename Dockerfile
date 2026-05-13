FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring exif pcntl bcmath gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev --ignore-platform-req=ext-zip

RUN php artisan key:generate
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

COPY --chown=www-data:www-data . /var/www/html
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN echo "server { \
    listen 80; \
    server_name _; \
    root /var/www/html/public; \
    add_header X-Frame-Options \"SAMEORIGIN\"; \
    add_header X-Content-Type-Options \"nosniff\"; \
    index index.php; \
    charset utf-8; \
    location / { \
        try_files \$uri \$uri/ /index.php?\$query_string; \
    } \
    location = /favicon.ico { access_log off; log_not_found off; } \
    location = /robots.txt { access_log off; log_not_found off; } \
    error_page 404 /index.php; \
    location ~ \.php$ { \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name; \
        include fastcgi_params; \
    } \
    location ~ /\.(?!well-known).* { \
        deny all; \
    } \
}" > /etc/nginx/sites-enabled/default

EXPOSE 80

CMD service nginx start && php-fpm
