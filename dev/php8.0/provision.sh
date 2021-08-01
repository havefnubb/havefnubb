#!/bin/bash

ROOTDIR="/hfnu"
MYSQL_VERSION="5."
PHP_VERSION="8.0"
APPNAME="havefnubb"
APPDIR="$ROOTDIR/$APPNAME"
VAGRANTDIR="/vagrantscripts"
APPHOSTNAME="havefnubb.local"
APPHOSTNAME2=""
FPM_SOCK="php\\/php8.0-fpm.sock"

source $VAGRANTDIR/common_provision.sh