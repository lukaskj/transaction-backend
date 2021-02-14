#!/bin/sh

cd $WORKDIR
cp /var/www/docker/.env /var/www/.env
composer install
/opt/wait-for-it.sh transaction-mysql:3306 -t 60 -- php artisan migrate --seed --force
php-fpm