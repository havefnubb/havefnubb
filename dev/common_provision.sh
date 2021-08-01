#!/bin/bash

source $VAGRANTDIR/system.sh

initsystem

apt-get -y install redis-server

apt-get -y install php-xdebug
cp $VAGRANTDIR/xdebug.ini /etc/php/$PHP_VERSION/mods-available/
service php${PHP_VERSION}-fpm restart

#resetComposer $APPDIR

# install phpunit
#if [ ! -f /usr/bin/phpunit ]; then
#    ln -s $APPDIR/vendor/bin/phpunit  /usr/bin/phpunit
#fi


source $VAGRANTDIR/reset_app.sh

echo "Done."
