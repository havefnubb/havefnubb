<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require ('havefnubb/application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

jApp::loadConfig('hfnuadmin/config.ini.php');    
jApp::setCoord(new jCoordinator());
jApp::coord()->process(new jClassicRequest());
