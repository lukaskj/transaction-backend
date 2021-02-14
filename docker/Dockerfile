FROM lukaskj/laravel-docker:7.4-fpm

ENV WORKDIR /var/www

WORKDIR /var/www

ADD ./ /var/www/

ENTRYPOINT ["/var/www/docker/scripts/start-app.sh"]