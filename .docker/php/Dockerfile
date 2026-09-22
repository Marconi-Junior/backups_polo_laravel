FROM php:8.5-apache

ARG UID
ARG GID

RUN apt-get update && apt-get install -y \
    git \
    cron \
    unzip \
    zip \
    curl \
    # nodejs \
    # npm \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown ${UID}:${GID} /

RUN curl -sL https://deb.nodesource.com/setup_22.x | bash -
RUN apt-get install nodejs -y

COPY .docker/php/apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html

RUN groupadd -g ${UID} www
RUN useradd -u ${GID} -ms /bin/bash -g www www

COPY --chown=www-data:www-data . /var/www

USER ${UID}