#!/bin/bash

ROOTDIR="/hfnu"
MYSQL_VERSION="5.3"
PHP_VERSION="5.3"
PHP53="yes"
APPNAME="havefnubb"
APPDIR="$ROOTDIR/$APPNAME"
VAGRANTDIR="/vagrantscripts"
APPHOSTNAME="havefnubb.local"
APPHOSTNAME2=""
FPM_SOCK="php5-fpm.sock"

source $VAGRANTDIR/common_provision.sh
