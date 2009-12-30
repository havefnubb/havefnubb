<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerApp extends jInstallerBase{
	function __construct($path){
	}
	function getConfig($entrypoint){
		 throw new Exception('not implemented');
		$filename = '';
		return new jIniMutliFilesModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php',
										  JELIX_APP_CONFIG_PATH.$filename);
	}
	function addEntryPoint($filename, $type, $configFilename){
		throw new Exception('not implemented');
	}
	function removeEntryPoint($filename){
		throw new Exception('not implemented');
	}
}