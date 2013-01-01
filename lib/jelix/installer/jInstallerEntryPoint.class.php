<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @copyright   2009-2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerEntryPoint{
	public $config;
	public $configFile;
	public $configIni;
	public $isCliScript;
	public $scriptName;
	public $file;
	public $type;
	function __construct($defaultConfig,$configFile,$file,$type){
		$this->type=$type;
		$this->isCliScript=($type=='cmdline');
		$this->configFile=$configFile;
		$this->scriptName=($this->isCliScript?$file:'/'.$file);
		$this->file=$file;
		$this->configIni=new jIniMultiFilesModifier($defaultConfig,jApp::configPath($configFile));
		$this->config=jConfigCompiler::read($configFile,true,
											$this->isCliScript,
											$this->scriptName);
	}
	function getEpId(){
		return $this->config->urlengine['urlScriptId'];
	}
	function getModulesList(){
		return $this->config->_allModulesPathList;
	}
	function getModule($moduleName){
		return new jInstallerModuleInfos($moduleName,$this->config->modules);
	}
}
