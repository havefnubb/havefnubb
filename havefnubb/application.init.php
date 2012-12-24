<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/*
require (dirname(__FILE__).'/../lib/jelix/init.php');

define ('JELIX_APP_PATH', dirname (__FILE__).DIRECTORY_SEPARATOR); // don't change

define ('JELIX_APP_TEMP_PATH',    realpath(JELIX_APP_PATH.'../temp/havefnubb/').DIRECTORY_SEPARATOR);
define ('JELIX_APP_VAR_PATH',     JELIX_APP_PATH.'var/'.DIRECTORY_SEPARATOR);
define ('JELIX_APP_LOG_PATH',     JELIX_APP_PATH.'var/log/'.DIRECTORY_SEPARATOR);
define ('JELIX_APP_CONFIG_PATH',  JELIX_APP_PATH.'var/config/'.DIRECTORY_SEPARATOR);
define ('JELIX_APP_WWW_PATH',     realpath(JELIX_APP_PATH.'../').DIRECTORY_SEPARATOR);
define ('JELIX_APP_CMD_PATH',     JELIX_APP_PATH.'scripts/'.DIRECTORY_SEPARATOR);
*/

$appPath = dirname (__FILE__).'/';
require ($appPath.'../lib/jelix/init.php');

jApp::initPaths(
    $appPath, // application path
    $appPath. '../', // www path
    $appPath.'var/', // var path
    $appPath.'var/log/', // log path
    $appPath.'var/config/', // config path
    $appPath.'scripts/' //script path
);
jApp::setTempBasePath(realpath($appPath.'../temp/havefnubb/').'/');
