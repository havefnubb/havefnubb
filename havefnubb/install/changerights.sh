#!/bin/bash

THIS_PATH=$(dirname $0)
cd $THIS_PATH/../../
chmod -R og-w *
cd havefnubb/var/config
sudo chown :www-data . defaultconfig.ini.php profiles.ini.php havefnubb/config.ini.php havefnubb/flood.coord.ini.php havefnubb/activeusers.coord.ini.php
chmod g+w . defaultconfig.ini.php profiles.ini.php havefnubb/config.ini.php havefnubb/flood.coord.ini.php havefnubb/activeusers.coord.ini.php
cd ..
sudo chown -R :www-data log
chmod -R g+w log
cd ../..
sudo chown -R :www-data temp/havefnubb/ cache/ files/
chmod -R g+w temp/havefnubb/ cache/ files/
