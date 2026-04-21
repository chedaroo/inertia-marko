FROM php:8.5-cli

RUN apt-get update && apt-get install -y git unzip && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
