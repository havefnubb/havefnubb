#!/bin/bash

THIS_PATH=$(dirname $0)
cd $THIS_PATH/../../
chmod -R og-w *
cd havefnubb/var/config
sudo chown :www-data . localconfig.ini.php profiles.ini.php
chmod g+w . localconfig.ini.php profiles.ini.php
cd ..
sudo chown -R :www-data log
chmod -R g+w log
cd ../..
sudo chown -R :www-data temp/havefnubb/ cache/ files/
chmod -R g+w temp/havefnubb/ cache/ files/
