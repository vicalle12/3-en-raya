FROM php:8.0.0-fpm-alpine
WORKDIR /var/www/app

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ bash icu-dev libzip-dev \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        intl \
        zip

ADD dockers/etc/infrastructure/php/extensions/xdebug.sh /root/install-xdebug.sh
RUN apk add git
RUN sh /root/install-xdebug.sh

RUN pecl install apcu
RUN docker-php-ext-enable \
        apcu

RUN curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

COPY dockers/etc/infrastructure/php/ /usr/local/etc/php/

COPY .. /var/www/app

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install