<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurentj
* @copyright 2008-2011 FoxMaSk, 2012-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

$appPath = __DIR__.'/';
require (__DIR__.'/vendor/autoload.php');

jApp::initPaths(
    $appPath,
    realpath($appPath.'../').DIRECTORY_SEPARATOR,
    $appPath.'var/'.DIRECTORY_SEPARATOR,
    $appPath.'var/log/'.DIRECTORY_SEPARATOR,
    $appPath.'var/config/'.DIRECTORY_SEPARATOR,
    $appPath.'scripts/'.DIRECTORY_SEPARATOR
);

jApp::setTempBasePath(realpath($appPath.'temp/havefnubb/').'/');

jApp::declareModulesDir(array(
    __DIR__.'/modules/',
    __DIR__.'/admin-modules/',
    __DIR__.'/modules-hook/'
));
jApp::declarePluginsDir(array(
    __DIR__.'/plugins'
));

require (__DIR__.'/vendor/jelix_app_path.php');
