FROM php:8.3-cli-alpine AS builder

RUN apk add zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY /src /app

WORKDIR /app

RUN composer install

RUN cp .env.example .env && php artisan key:generate



FROM php:8.3-cli-alpine

RUN apk add autoconf g++ make libstdc++ pkgconfig brotli-dev

RUN pecl install swoole 
RUN docker-php-ext-enable swoole

RUN docker-php-ext-install pdo pdo_mysql pcntl

COPY --from=builder /app /app

RUN chown -R www-data:www-data /app

WORKDIR /app

EXPOSE 8000

CMD ["/bin/sh", "-c", "php artisan octane:start  --server=swoole --host=0.0.0.0 --port=8000"]