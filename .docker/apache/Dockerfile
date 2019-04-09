FROM php:7.2-apache

LABEL maintainer="ibrahima.diallo@wolterskluwer.com"

RUN apt-get update && apt-get install -y \
    libmcrypt-dev \
    libsqlite3-dev \
    libssl-dev \
    libcurl3-dev \
    libxml2-dev \
    libzzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    pdftk \
    wget \
    && docker-php-ext-install iconv json mbstring mysqli pdo_mysql pdo_sqlite phar curl ftp hash session simplexml tokenizer xml xmlrpc zip soap \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install gd

RUN wget https://getcomposer.org/composer.phar \
    && chmod +x composer.phar \
    && mv composer.phar /usr/local/bin/composer \
    && composer config --global repo.packagist composer https://packagist.org

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN a2enmod ssl

CMD ["apache2-foreground"]