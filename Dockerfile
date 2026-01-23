FROM composer:2.7.7 AS composer-install
WORKDIR /var/www/html
COPY composer.json composer.lock symfony.lock ./
RUN composer install --no-dev --no-scripts

FROM node:20 AS webpack
WORKDIR /var/www/html
COPY package.json package-lock.json ./
RUN npm ci
COPY webpack.config.js postcss.config.js tailwind.config.js ./
COPY assets/ assets/
COPY templates/ templates/
RUN npm run build

FROM php:8.3-apache AS web
RUN apt-get update && apt-get install -y \
    # needed for intl php module
    libicu-dev \
    # needed for PostgreSQL
    libpq-dev
RUN docker-php-ext-install \
    bcmath \
    intl \
    opcache \
    pdo_pgsql
RUN a2enmod rewrite && a2enmod headers && a2enmod expires
COPY docker/apache/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY docker/apache/apache.conf /etc/apache2/conf-enabled/apache.conf
COPY docker/php/php.base.ini $PHP_INI_DIR/conf.d/
WORKDIR /var/www/html
COPY . ./
COPY --from=composer-install /var/www/html/vendor vendor/
COPY --from=webpack /var/www/html/public/build public/build/
RUN mkdir -p var/cache var/log
ENV APP_ENV=prod
RUN bin/console assets:install
RUN chown --recursive www-data:www-data var/

FROM web AS dev
RUN apt-get update && apt-get install -y git unzip
COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer
COPY --from=node:20 /usr/local/bin/node /usr/local/bin/node
COPY --from=node:20 /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm
RUN useradd -m -s /bin/bash dev && chown -R dev:dev /var/www/html
ENV APP_ENV=dev
