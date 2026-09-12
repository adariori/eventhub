# --- Étape 1 : compiler les assets front (Tailwind, JS) ---
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js tailwind.config.js postcss.config.js ./
RUN npm run build

# --- Étape 2 : installer les dépendances PHP ---
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev

# --- Étape 3 : image finale ---
FROM php:8.4-cli-alpine

# Bibliothèques nécessaires au RUNTIME (gardées) + leurs "-dev" nécessaires
# seulement à la COMPILATION des extensions (supprimées une fois compilées).
RUN apk add --no-cache libpq libzip icu oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev libzip-dev icu-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring bcmath zip intl \
    && apk del .build-deps

WORKDIR /var/www/html

COPY --from=vendor /app ./
COPY --from=assets /app/public/build ./public/build
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENV APP_ENV=production
ENV LOG_CHANNEL=stderr

EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
