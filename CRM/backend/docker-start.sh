#!/bin/sh
set -e

composer install --no-interaction

# baza tayyor bo'lguncha kutamiz
until php bin/console dbal:run-sql "SELECT 1" > /dev/null 2>&1; do
    echo "Baza kutilmoqda..."
    sleep 2
done

php bin/console doctrine:migrations:migrate --no-interaction

php -S 0.0.0.0:8000 -t public
