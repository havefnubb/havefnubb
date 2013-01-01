<?php
// check version from 2 files :
// from the defaultconfig.ini.php file 
//     and 
// from the VERSION file and compare them
$currentVersion     = jIniFile::read(jApp::configPath().'defaultconfig.ini.php');
$newVersion         = jFile::read(jApp::appPath().'VERSION');
if (trim($currentVersion['havefnubb']['version']) == trim($newVersion))
    $alreadyInstalled = true;    
else
    $alreadyInstalled = false;
// check if the application is already installed
$appInstalled = file_exists(jApp::configPath().'installer.ini.php');
