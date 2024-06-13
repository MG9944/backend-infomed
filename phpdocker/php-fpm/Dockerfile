FROM phpdockerio/php:8.1-fpm
WORKDIR "/application"
# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive


RUN apt-get update \
    && apt-get -y --no-install-recommends install php8.1-mysql php8.1-redis php8.1-yaml nano php-pear php8.1-dev make \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && PHP_OPENSSL=yes

RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN curl -sSL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.5

RUN pecl install xdebug-3.1.0
