<?php

$locales=array(
    'title'=>'Welcome',
    'introduction'=>'Introduction',
    'process.description'=>'this installation process is composed of several parts, starting from checking
                            the compatibility of your system to the database access and setting up the forum',
    'process.rename.dist.file'=>'Rename the config files',
    'process.rename.dist.file.desc'=>'Before you begin, rename the config file <strong>defaultconfig.ini.php.dist</strong>
                in <strong>defaultconfig.ini.php</strong> and <strong>dbprofils.ini.php.dist</strong> in
                <strong>dbprofils.ini.php</strong> located in the folder var/config',
    'rights'=>'Rights Access',
    'rights.description'=>'Don\'t forget to change your rights access on all your folders and files to read-only for the web server,
                            <br/><strong>except for these files which have to be writable:</strong><ol>
                            <li>cache/ and files/</li>
                            <li>havefnubb/var/config/defaultconfig.ini.php</li>
                            <li>havefnubb/var/config/dbprofils.ini.php</li>
                            <li>havefnubb/var/config/havefnubb/config.ini.php</li>
                            <li>havefnubb/var/config/havefnubb/flood.coord.ini.php</li>
                            <li>havefnubb/var/config/havefnubb/activeusers.coord.ini.php</li>
                            </ol>
                            <p>On a Windows machine, you probably don\'t care about them.</p>
                            ',
    'rights.debian.title'=>'Rights on a debian/ubuntu server',
    'rights.debian.description'=>'On a debian/ubuntu server, most of time these files should have www-data as group owner and should have group writable.
    Run the havefnubb/install/changerights.sh script on the command line.',
);
