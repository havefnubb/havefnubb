#!/bin/bash

ROOTDIR="/hfnu"
MYSQL_VERSION="5.5"
PHP_VERSION="5.6"
APPNAME="havefnubb"
APPDIR="$ROOTDIR/$APPNAME"
VAGRANTDIR="/vagrantscripts"
APPHOSTNAME="havefnubb.local"
APPHOSTNAME2=""
FPM_SOCK="php\\/php5.6-fpm.sock"

source $VAGRANTDIR/common_provision.sh
