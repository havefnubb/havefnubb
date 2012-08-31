<?php
/**
* @package   havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license   GPL licence
*/
require_once (dirname(__FILE__).'/../application.init.php');
jApp::setEnv('install');

jAppManager::close();

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();

jAppManager::clearTemp();
jAppManager::open();