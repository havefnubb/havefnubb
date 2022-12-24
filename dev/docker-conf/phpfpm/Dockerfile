ARG PHP_VERSION
FROM 3liz/liz-php-fpm:${PHP_VERSION}

RUN apt update && apt upgrade -y && apt -y install \
    php${PHP_VERSION}-xdebug \
    mariadb-client \
    unzip \
    ; \
    apt clean

COPY profile.start /etc/profile.d/start
COPY hfnu_entrypoint.sh /bin/entrypoint.d/
COPY appctl.sh /bin/
RUN chmod 755 /bin/entrypoint.d/hfnu_entrypoint.sh /bin/appctl.sh
