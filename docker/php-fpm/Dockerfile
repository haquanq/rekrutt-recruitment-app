FROM php:8.4-fpm-alpine 

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql zip bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY --chown=www-data:www-data /apps/server .
COPY /docker/php-fpm/php.ini /usr/local/etc/php/php.ini
COPY /docker/php-fpm/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]

EXPOSE 9000
CMD ["php-fpm"]