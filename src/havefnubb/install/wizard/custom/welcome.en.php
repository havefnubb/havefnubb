<?php
include (__DIR__.'/../../version.php');

$versionMsg =   '<ul class="jelix-msg">'.
                '<li class="jelix-msg-item-ok">';

if ($appInstalled) {
	$versionMsg .= 'Installed version : '.$currentVersion['havefnubb']['version'] .
                '</li>'.
                '</ul>';

	if ($alreadyInstalled === false) 
	    $versionMsg .=   '<ul class="jelix-msg">'.
                    '<li class="jelix-msg-item-notice">'.
                    'New Version : '.
                    $newVersion.
                    '</li>'.
                    '</ul>';
}
else 
	$versionMsg .= 'Installation of the version ' . $newVersion . 
                '</li>'.
                '</ul>';

$locales=array(
    'title'=>'Welcome',
    'introduction'=>'Introduction',
    'version'=>$versionMsg,
    'process.description'=>'this installation process is composed of several parts, starting from checking
                            the compatibility of your system to the database access and setting up the forum',
    'process.rename.dist.file'=>'Rename the config files',
    'process.rename.dist.file.desc'=>'Before you begin, rename the config file <strong>localconfig.ini.php.dist</strong>
                to <strong>localconfig.ini.php</strong> and <strong>profiles.ini.php.dist</strong> to
                <strong>profiles.ini.php</strong> located in the folder var/config',
    'rights'=>'Rights Access',
    'rights.description'=>'Don\'t forget to change your rights access on all your folders and files to read-only for the web server,
                            <br/><strong>except for these files which have to be writable:</strong><ol>
                            <li>cache/ and files/</li>
                            <li>havefnubb/var/config/localconfig.ini.php</li>
                            <li>havefnubb/var/config/profiles.ini.php</li>
                            <li>havefnubb/var/config/havefnubb/config.ini.php</li>
                            </ol>
                            <p>On a Windows machine, you probably don\'t care about them.</p>
                            ',
    'rights.debian.title'=>'Rights on a debian/ubuntu server',
    'rights.debian.description'=>'On a debian/ubuntu server, most of time these files should have www-data as group owner and should have group writable.
    Run the havefnubb/install/changerights.sh script on the command line.',
);
