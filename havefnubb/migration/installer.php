<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2010 Olivier Demah
* @link      https://havefnubb.jelix.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (dirname(__FILE__).'/../application.init.php');
jApp::setEnv('install');

jAppManager::close();

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();

jAppManager::clearTemp();
jAppManager::open();