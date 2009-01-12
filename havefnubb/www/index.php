<?php
/**
* @package   havefnubb
* @subpackage 
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://www.havefnu.com
* @licence    All right reserved
*/

require ('../application.init.php');
require (JELIX_LIB_CORE_PATH.'request/jClassicRequest.class.php');

$config_file = 'index/config.ini.php';

$jelix = new jCoordinator($config_file);
$jelix->process(new jClassicRequest());


