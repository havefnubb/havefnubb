<?php
/**
* @package   havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   GPL licence
*/
require_once (dirname(__FILE__).'/../application.init.php');
jApp::setEnv('install');

jAppManager::close();

if (file_exists(jApp::configPath('dbprofils.ini.php')) && ! file_exists(jApp::configPath('profiles.ini.php'))) {
    $profilesfile = jApp::configPath('profiles.ini.php');
    file_put_contents($profilesfile,";<?php die(''); ?>
;for security reasons, don't remove or modify the first line");
    $profiles = new jIniFileModifier($profilesfile);
    $dbProfiles = new jIniFileModifier(jApp::configPath('dbprofils.ini.php'));
    $profiles->import($dbProfiles, 'jdb', ':');
    $profiles->save();
}

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();

jAppManager::clearTemp();
jAppManager::open();