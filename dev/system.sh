#!/bin/bash

DISTRO=""
if [ -f /etc/os-release ]; then
    . /etc/os-release
    if [ "$VERSION_ID" = "8" ]; then
        DISTRO="jessie"
    else
        if [ "$VERSION_ID" = "9" ]; then
            DISTRO="stretch"
        else
          if [ "$VERSION_ID" = "10" ]; then
              DISTRO="buster"
          fi
        fi
    fi
fi

function initsystem () {
    # create hostname
    HOST=`grep $APPHOSTNAME /etc/hosts`
    if [ "$HOST" == "" ]; then
        echo "127.0.0.1 $APPHOSTNAME $APPHOSTNAME2" >> /etc/hosts
    fi
    hostname $APPHOSTNAME
    echo "$APPHOSTNAME" > /etc/hostname
    
    # local time
    echo "Europe/Paris" > /etc/timezone
    cp /usr/share/zoneinfo/Europe/Paris /etc/localtime
    locale-gen fr_FR.UTF-8
    update-locale LC_ALL=fr_FR.UTF-8

    # install all packages
    apt-get update
    apt-get install -y software-properties-common apt-transport-https
    if [ "$DISTRO" != "jessie" ]; then
        apt-get install -y dirmngr
    fi
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
    echo "deb https://packages.sury.org/php $DISTRO main" > /etc/apt/sources.list.d/sury_php.list

    if [ "$DISTRO" != "jessie" ]; then
        if [ ! -f "/etc/apt/sources.list.d/mysql.list" ]; then
            echo -e "deb http://repo.mysql.com/apt/debian/ stretch mysql-5.7\ndeb-src http://repo.mysql.com/apt/debian/ stretch mysql-5.7" > /etc/apt/sources.list.d/mysql.list
            wget -O /tmp/RPM-GPG-KEY-mysql https://repo.mysql.com/RPM-GPG-KEY-mysql
            apt-key add /tmp/RPM-GPG-KEY-mysql
        fi
    fi

    apt-get update
    apt-get -y upgrade
    apt-get -y install debconf-utils
    export DEBIAN_FRONTEND=noninteractive
    echo "mysql-server-$MYSQL_VERSION mysql-server/root_password password jelix" | debconf-set-selections
    echo "mysql-server-$MYSQL_VERSION mysql-server/root_password_again password jelix" | debconf-set-selections

    apt-get -y install nginx
    apt-get -y install  php${PHP_VERSION}-fpm \
                        php${PHP_VERSION}-cli \
                        php${PHP_VERSION}-curl \
                        php${PHP_VERSION}-gd \
                        php${PHP_VERSION}-intl \
                        php${PHP_VERSION}-ldap \
                        php${PHP_VERSION}-mysql \
                        php${PHP_VERSION}-pgsql \
                        php${PHP_VERSION}-sqlite3 \
                        php${PHP_VERSION}-soap \
                        php${PHP_VERSION}-dba \
                        php${PHP_VERSION}-xml \
                        php${PHP_VERSION}-mbstring \
                        php-memcached \
                        php-redis
    sed -i "/^user = www-data/c\user = vagrant" /etc/php/$PHP_VERSION/fpm/pool.d/www.conf
    sed -i "/^group = www-data/c\group = vagrant" /etc/php/$PHP_VERSION/fpm/pool.d/www.conf
    sed -i "/display_errors = Off/c\display_errors = On" /etc/php/$PHP_VERSION/fpm/php.ini
    sed -i "/display_errors = Off/c\display_errors = On" /etc/php/$PHP_VERSION/cli/php.ini

    service php${PHP_VERSION}-fpm restart
    apt-get -y install mysql-server mysql-client
    apt-get -y install git vim unzip curl
    
    # install default vhost for apache
    cp $VAGRANTDIR/vhost /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- s/__APPHOSTNAME__/$APPHOSTNAME/g /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- s/__APPHOSTNAME2__/$APPHOSTNAME2/g /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- "s!__APPDIR__!$APPDIR!g" /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- "s!__ROOTDIR__!$ROOTDIR!g" /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- s/__APPNAME__/$APPNAME/g /etc/nginx/sites-available/$APPNAME.conf
    sed -i -- s/__FPM_SOCK__/$FPM_SOCK/g /etc/nginx/sites-available/$APPNAME.conf

    if [ ! -f /etc/nginx/sites-enabled/010-$APPNAME.conf ]; then
        ln -s /etc/nginx/sites-available/$APPNAME.conf /etc/nginx/sites-enabled/010-$APPNAME.conf
    fi
    if [ -f "/etc/nginx/sites-enabled/default" ]; then
        rm -f "/etc/nginx/sites-enabled/default"
    fi

    # restart nginx
    service nginx restart
    
    echo "Install composer.."
    if [ ! -f /usr/local/bin/composer ]; then
        curl -sS https://getcomposer.org/installer | php
        mv composer.phar /usr/local/bin/composer
    fi

    echo 'alias ll="ls -al"' > /home/vagrant/.bash_aliases
}


function resetJelixMysql() {
    local base="$1"
    local login="$2"
    local pass="$3"

    # create a database into mysql + users
    if [ -d /var/lib/mysql/$base/ ]; then
        mysql -u root -pjelix -e "DROP DATABASE IF EXISTS $base;DROP USER IF EXISTS $login;"
    fi

    mysql -u root -pjelix -e "CREATE DATABASE IF NOT EXISTS $base CHARACTER SET utf8;CREATE USER $login IDENTIFIED BY '$pass';GRANT ALL ON $base.* TO $login;FLUSH PRIVILEGES;"
}

function resetJelixTemp() {
    local appdir="$1"
    if [ ! -d $appdir/temp/$APPNAME ]; then
        mkdir -p $appdir/temp/$APPNAME
    else
        rm -rf $appdir/temp/$APPNAME/*
    fi
    touch $appdir/temp/$APPNAME/.dummy
}

function resetJelixInstall() {
    local appdir="$1"

    if [ -f $appdir/var/config/CLOSED ]; then
        rm -f $appdir/var/config/CLOSED
    fi

    if [ ! -d $appdir/var/log ]; then
        mkdir $appdir/var/log
    fi
    if [ -f $appdir/var/config/profiles.ini.php.dist ]; then
        cp -a $appdir/var/config/profiles.ini.php.dist $appdir/var/config/profiles.ini.php
    fi
    if [ -f $appdir/var/config/localconfig.ini.php.dist ]; then
        cp -a $appdir/var/config/localconfig.ini.php.dist $appdir/var/config/localconfig.ini.php
    fi
    if [ -f $appdir/var/config/installer.ini.php ]; then
        rm -f $appdir/var/config/installer.ini.php
    fi
    resetJelixTemp $appdir
}

function runComposer() {
    cd $1
    su -c "composer install" vagrant
}

function resetComposer() {
    cd $1
    if [ -f composer.lock ]; then
        rm -f composer.lock
    fi
    su -c "composer install" vagrant
}

function initapp() {
    php $1/install/installer.php
}

