FROM php:7.2-fpm-alpine
# lumen packages
RUN docker-php-ext-install mbstring tokenizer mysqli pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer