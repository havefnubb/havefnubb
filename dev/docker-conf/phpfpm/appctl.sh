#!/bin/bash
ROOTDIR="/app/src"
APPDIR="$ROOTDIR/havefnubb"
APP_USER=userphp
APP_GROUP=groupphp

COMMAND="$1"
shift

if [ "$COMMAND" == "" ]; then
    echo "Error: command is missing"
    exit 1;
fi

function resetJelixTemp() {
    echo "--- Reset  temp files in $1"
    local appdir="$1"
    if [ ! -d $appdir/var/log ]; then
        mkdir $appdir/var/log
        chown $APP_USER:$APP_GROUP $appdir/var/log
    fi
    if [ ! -d $appdir/temp/havefnubb/ ]; then
        mkdir -p $appdir/temp/havefnubb/
        chown $APP_USER:$APP_GROUP $appdir/temp/havefnubb
    else
        rm -rf $appdir/temp/havefnubb/*
    fi
    touch $appdir/temp/.dummy
    chown $APP_USER:$APP_GROUP $appdir/temp/.dummy
}

function resetApp() {
    echo "--- Reset testapp configuration files in $1"
    local appdir="$1"
    if [ -f $appdir/var/config/CLOSED ]; then
        rm -f $appdir/var/config/CLOSED
    fi

    for vardir in log mails uploads; do
      if [ ! -d $appdir/var/$vardir ]; then
          mkdir $appdir/var/$vardir
      else
          rm -rf $appdir/var/$vardir/*
      fi
      touch $appdir/var/$vardir/.dummy
    done

    if [ -f $appdir/var/config/profiles.ini.docker.php.dist ]; then
        cp $appdir/var/config/profiles.ini.docker.php.dist $appdir/var/config/profiles.ini.php
    fi
    if [ -f $appdir/var/config/localconfig.dev.ini.php.dist ]; then
        cp $appdir/var/config/localconfig.dev.ini.php.dist $appdir/var/config/localconfig.ini.php
    fi
    chown -R $APP_USER:$APP_GROUP $appdir/var/config/profiles.ini.php $appdir/var/config/localconfig.ini.php

    if [ -f $appdir/var/config/installer.ini.php ]; then
        rm -f $appdir/var/config/installer.ini.php
    fi
    if [ -f $appdir/var/config/liveconfig.ini.php ]; then
        rm -f $appdir/var/config/liveconfig.ini.php
    fi

    setRights $appdir
    launchInstaller $appdir
}

function resetMysql() {
    echo "--- Reset mysql database for database $1"
    local base="$1"
    local login="$2"
    local pass="$3"

    mysql -h mysql -u $login -p$pass -Nse 'show tables' $base | while read table; do mysql -h mysql -u $login -p$pass -e "drop table $table" $base; done
}

function launchInstaller() {
    echo "--- Launch app installer in $1"
    local appdir="$1"
    su $APP_USER -c "php $appdir/install/installer.php --verbose"
}

function setRights() {
    echo "--- Set rights on directories and files in $1"
    local appdir="$1"
    USER="$2"
    GROUP="$3"

    if [ "$USER" = "" ]; then
        USER="$APP_USER"
    fi

    if [ "$GROUP" = "" ]; then
        GROUP="$APP_GROUP"
    fi

    DIRS="$appdir/var/config $appdir/var/db $appdir/var/log $appdir/var/mails $appdir/var/uploads $appdir/temp/havefnubb"
    for VARDIR in $DIRS; do
      if [ ! -d $VARDIR ]; then
        mkdir -p $VARDIR
      fi
      chown -R $USER:$GROUP $VARDIR
      chmod -R ug+w $VARDIR
      chmod -R o-w $VARDIR
    done

}

function composerInstall() {
    echo "--- Install Composer packages"
    /bin/helpers.sh composer-install $1
}

function composerUpdate() {
    echo "--- Update Composer packages"
    /bin/helpers.sh composer-update $1
}

function launch() {
    echo "--- Launch hfnu setup in $1"
    local appdir="$1"
    if [ ! -f $appdir/var/config/profiles.ini.php ]; then
        cp $appdir/var/config/profiles.ini.docker.php.dist $appdir/var/config/profiles.ini.php
    fi
    if [ ! -f $appdir/var/config/localconfig.ini.php ]; then
        cp $appdir/var/config/localconfig.ini.php.dist $appdir/var/config/localconfig.ini.php
    fi
    chown -R $APP_USER:$APP_GROUP $appdir/var/config/profiles.ini.php $APPDIR/var/config/localconfig.ini.php

    if [ ! -d $appdir/vendor ]; then
      composerInstall
    fi

    resetApp $appdir
    launchInstaller $appdir
    setRights $appdir
}


case $COMMAND in
    clean_tmp)
        resetJelixTemp $APPDIR
        ;;
    reset)
        resetMysql havefnubb havefnubb hfnu
        resetJelixTemp $APPDIR
        composerInstall $APPDIR
        resetApp $APPDIR
        ;;
    install)
        launchInstaller $APPDIR
        ;;
    rights)
        setRights $APPDIR
        ;;
    composer_install)
        composerInstall $APPDIR;;
    composer_update)
        composerUpdate $APPDIR;;
    unit-tests)
        UTCMD="cd $ROOTDIR/dev/tests/ && vendor/bin/phpunit  $@"
        su $APP_USER -c "$UTCMD"
        ;;
    *)
        echo "wrong command"
        exit 2
        ;;
esac

