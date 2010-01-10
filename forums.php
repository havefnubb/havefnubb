<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require ('havefnubb/application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'havefnubb/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());


