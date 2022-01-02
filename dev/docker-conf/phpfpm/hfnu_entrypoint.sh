#!/bin/sh

set -e
set -x

if [ -n "$TLS_CA_CRT_FILENAME" ]; then
    cp -a /customcerts/$TLS_CA_CRT_FILENAME /etc/ssl/certs/tests_CA.crt
    chown root:groupphp /etc/ssl/certs/tests_CA.crt
    chmod 0444 /etc/ssl/certs/tests_CA.crt
fi

APPDIR="/app/src/havefnubb"

if [ ! -f $APPDIR/var/config/profiles.ini.php ]; then
    echo "It seems databases and testapp are not configured yet. Please execute"
    echo "   ./app-ctl reset"
    echo "in order to setup databases and testapp, after containers will be ready."
fi

echo "launch exec $@"
exec "$@"
