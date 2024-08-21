FROM php:8.3.8 as php

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev 
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

WORKDIR /var/www
#

# Copy the rest of the application code
COPY . .
RUN apt-get install -y npm \
    && npm install
COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

ENV PORT=8000
ENTRYPOINT ["docker/entrypoint.sh"]
