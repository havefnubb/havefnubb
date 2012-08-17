<?php
// check version from 2 files :
// from the defaultconfig.ini.php file 
//     and 
// from the VERSION file and compare them
$currentVersion     = jIniFile::read(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
$newVersion         = jFile::read(JELIX_APP_PATH.'VERSION');
if (trim($currentVersion['havefnubb']['version']) == trim($newVersion))
    $alreadyInstalled = true;    
else
    $alreadyInstalled = false;
// check if the application is already installed
$appInstalled = file_exists(JELIX_APP_CONFIG_PATH.'installer.ini.php');
