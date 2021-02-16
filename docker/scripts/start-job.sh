#!/bin/bash
cp /var/www/docker/.env.docker /var/www/.env
chown www-data:www-data -R storage/
/opt/wait-for-it.sh transaction-mysql:3306 -t 60 -- php artisan queue:work