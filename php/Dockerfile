FROM php:8.2-fpm-alpine
#RUN sed -i 's/9000/9001/' /usr/local/etc/php-fpm.d/zz-docker.conf
#RUN sed -i 's/9000/9001/' /usr/local/etc/php-fpm.d/www.conf
RUN apk update && apk add --no-cache linux-headers $PHPIZE_DEPS supervisor
RUN pecl install xdebug-3.2.2
RUN docker-php-ext-install pdo pdo_mysql sockets
RUN docker-php-ext-enable xdebug
COPY --from=composer:2.6.5 /usr/bin/composer /usr/bin/composer
COPY php/supervisord.conf /etc/supervisord.conf
COPY php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/testKMA
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install
COPY . .

CMD ["supervisord"]

