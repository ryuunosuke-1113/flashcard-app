#!/bin/sh

set -e

PORT="${PORT:-10000}"

sed -i "s/Listen 80/Listen ${PORT}/" \
    /etc/apache2/ports.conf

sed -i "s/:80/:${PORT}/" \
    /etc/apache2/sites-available/000-default.conf

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force

php artisan storage:link || true

exec apache2-foreground