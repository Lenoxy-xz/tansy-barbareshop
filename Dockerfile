FROM php:8.3-apache

# Ekstensi PHP: PostgreSQL (Neon), MySQL (opsional), zip. mbstring/xml/tokenizer sudah bawaan image.
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev libzip-dev unzip \
 && docker-php-ext-install pdo_pgsql pdo_mysql zip \
 && a2enmod rewrite headers \
 && a2dissite 000-default \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html

# Dependency dulu (memanfaatkan cache layer Docker); tanpa paket dev
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts \
 && rm -f bootstrap/cache/*.php

# Konfigurasi Apache dan entrypoint
COPY docker/vhost.conf /etc/apache2/sites-available/tansy.conf
RUN a2ensite tansy \
 && sed -i '/^Listen 80$/d;/^\s*Listen 443$/d' /etc/apache2/ports.conf \
 && sed -i 's/\r$//' docker/entrypoint.sh /etc/apache2/sites-available/tansy.conf \
 && chmod +x docker/entrypoint.sh

ENV PORT=8080 APP_ENV=production APP_DEBUG=false LOG_CHANNEL=stderr
EXPOSE 8080
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
