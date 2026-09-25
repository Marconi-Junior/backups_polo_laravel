# Estágio temporário: Obtém os utilitários da imagem oficial do PostgreSQL 18 baseada em Debian
FROM postgres:18-bookworm AS postgres_binaries

# Estágio principal da aplicação Laravel
FROM php:8.5-apache

ARG UID
ARG GID

# 1. Instala utilitários essenciais, bibliotecas do Postgres e o Node.js
RUN apt-get update && apt-get install -y \
    git \
    cron \
    unzip \
    zip \
    curl \
    libpq-dev \
    libpq5 \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql

# 2. Cópia dos binários compatíveis do pg_dump v18
COPY --from=postgres_binaries /usr/lib/postgresql/18/bin/pg_dump /usr/local/bin/pg_dump
COPY --from=postgres_binaries /usr/lib/postgresql/18/bin/pg_restore /usr/local/bin/pg_restore
COPY --from=postgres_binaries /usr/lib/postgresql/18/bin/psql /usr/local/bin/psql

# 3. Instalação do Composer
RUN curl -sS https://getcomposer.org | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown ${UID}:${GID} /

COPY .docker/php/apache-config.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . /var/www/html

RUN groupadd -g ${UID} www
RUN useradd -u ${GID} -ms /bin/bash -g www www

COPY --chown=www-data:www-data . /var/www

USER ${UID}
