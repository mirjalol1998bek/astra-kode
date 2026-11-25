FROM php:8.5-cli

RUN apt-get update \
    && apt-get install -y git unzip libicu-dev \
    && docker-php-ext-install pdo_mysql intl \
    && rm -rf /var/lib/apt/lists/*

RUN echo "date.timezone=Asia/Tashkent" > /usr/local/etc/php/conf.d/timezone.ini

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

CMD ["sh", "docker-start.sh"]
