<?php
include (dirname(__FILE__).'/../../version.php');

$versionMsg =   '<ul class="jelix-msg">'.
                '<li class="jelix-msg-item-ok">'.
                'Installed Version : '.
                $currentVersion['havefnubb']['version'].
                '</li>'.
                '</ul>';

if ($alreadyInstalled === false) {
    $versionMsg .=   '<ul class="jelix-msg">'.
                    '<li class="jelix-msg-item-notice">'.
                    'New Version : '.
                    $newVersion.
                    '</li>'.
                    '</ul>';
}

$locales=array(
    'title'=>'Welcome',
    'introduction'=>'Introduction',
    'version'=>$versionMsg,
    'process.description'=>'this installation process is composed of several parts, starting from checking
                            the compatibility of your system to the update of your database and modules',
    'rights'=>'Rights Access',
    'rights.description'=>'Don\'t forget to change your rights access on all your folders and files to read-only for the web server,
                            <br/><strong>except for these files which have to be writable:</strong><ol>
                            <li>cache/ and files/</li>
                            <li>havefnubb/var/config/defaultconfig.ini.php</li>
                            <li>havefnubb/var/config/profiles.ini.php</li>
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
