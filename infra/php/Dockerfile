FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

# Instalando extensões necessárias do PHP
RUN apk update && apk add --update linux-headers nginx nodejs npm

ENV TZ=America/Sao_Paulo

RUN apk add --no-cache tzdata \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

RUN docker-php-ext-configure pcntl --enable-pcntl && docker-php-ext-install \
    pcntl

RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
    && pecl install -o -f redis \
    && pecl install xdebug \
    && docker-php-ext-enable redis.so \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-configure pdo_mysql && docker-php-ext-install pdo_mysql

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80

# Inicia o Nginx e o PHP-FPM
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]

# User default

# RUN addgroup -g 1000 laravel && adduser -G laravel -g laravel -s /bin/sh -D laravel

# RUN chown -R laravel /var/www/html

# USER laravel