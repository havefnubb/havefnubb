<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require ('havefnubb/application.init.php');
if(!file_exists(jApp::configPath().'installer.ini.php')){
    header("location: install.php");
}
else {
    require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

    checkAppOpened();
    jApp::loadConfig('havefnubb/config.ini.php');

#    $jelix = new jCoordinator($config_file);
#    $jelix->process(new jClassicRequest());

    jApp::setCoord(new jCoordinator());
    jApp::coord()->process(new jClassicRequest());



}
