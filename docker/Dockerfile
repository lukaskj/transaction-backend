FROM lukaskj/laravel-docker:7.4-fpm

ENV WORKDIR /var/www/

COPY ./ /var/www/

WORKDIR /var/www/

RUN [ "composer", "install" ]

