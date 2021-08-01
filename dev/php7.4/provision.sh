#!/bin/bash

ROOTDIR="/hfnu"
MYSQL_VERSION="5."
PHP_VERSION="7.4"
APPNAME="havefnubb"
APPDIR="$ROOTDIR/$APPNAME"
VAGRANTDIR="/vagrantscripts"
APPHOSTNAME="havefnubb.local"
APPHOSTNAME2=""
FPM_SOCK="php\\/php7.4-fpm.sock"

source $VAGRANTDIR/common_provision.sh