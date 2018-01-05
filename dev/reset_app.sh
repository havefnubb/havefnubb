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

if [ -f $appdir/var/config/defaultconfig.dev.ini.php.dist ]; then
    cp -a $appdir/var/config/defaultconfig.dev.ini.php.dist $appdir/var/config/defaultconfig.ini.php
fi
if [ -f $appdir/var/config/dbprofils.ini.php.dist ]; then
    cp -a $appdir/var/config/dbprofils.ini.php.dist $appdir/var/config/dbprofils.ini.php
fi

initapp $APPDIR
