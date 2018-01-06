#!/bin/bash
ROOTDIR="/hfnu"
APPNAME="havefnubb"
APPDIR="$ROOTDIR/$APPNAME"
VAGRANTDIR="/vagrantscripts"

source $VAGRANTDIR/system.sh

PHP_VERSION=$(php -r "echo phpversion();")

# --- testapp
resetJelixMysql $APPNAME havefnubb hfnu
resetJelixInstall $APPDIR

if [ -f $APPDIR/var/config/localconfig.dev.ini.php.dist ]; then
    cp -a $APPDIR/var/config/localconfig.dev.ini.php.dist $APPDIR/var/config/localconfig.ini.php
fi


initapp $APPDIR
