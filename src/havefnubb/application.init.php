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
require ($appPath.'lib/jelix/init.php');

jApp::initPaths(
    $appPath,
    realpath($appPath.'../').DIRECTORY_SEPARATOR,
    $appPath.'var/'.DIRECTORY_SEPARATOR,
    $appPath.'var/log/'.DIRECTORY_SEPARATOR,
    $appPath.'var/config/'.DIRECTORY_SEPARATOR,
    $appPath.'scripts/'.DIRECTORY_SEPARATOR
);

jApp::setTempBasePath(realpath($appPath.'temp/havefnubb/').'/');