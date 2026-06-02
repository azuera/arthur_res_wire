FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN git config --global --add safe.directory /var/www/html

RUN composer install --no-interaction --optimize-autoloader

RUN mkdir -p config/jwt && \
    openssl genrsa -out config/jwt/private.pem 4096 && \
    openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8000

CMD php /usr/bin/composer install --no-interaction && php -S 0.0.0.0:8000 -t public