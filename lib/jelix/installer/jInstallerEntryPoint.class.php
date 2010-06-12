<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2009-2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerModuleInfos{
	public $name;
	public $access;
	public $dbProfile;
	public $isInstalled;
	public $version;
	public $sessionId;
	public $entryPoint;
	function __construct($name,$entryPoint){
		$this->name=$name;
		$this->entryPoint=$entryPoint;
		$config=$entryPoint->config;
		$this->access=$config->modules[$name.'.access'];
		$this->dbProfile=$config->modules[$name.'.dbprofile'];
		$this->isInstalled=$config->modules[$name.'.installed'];
		$this->version=$config->modules[$name.'.version'];
		$this->sessionId=$config->modules[$name.'.sessionid'];
	}
}
class jInstallerEntryPoint{
	public $config;
	public $configFile;
	public $isCliScript;
	public $scriptName;
	public $file;
	public $type;
	function __construct($configFile,$file,$type){
		$this->type=$type;
		$this->isCliScript=($type=='cmdline');
		$this->configFile=$configFile;
		$this->scriptName=($this->isCliScript?$file:'/'.$file);
		$this->file=$file;
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
		return new jInstallerModuleInfos($moduleName,$this);
	}
	function isModuleInstalled($moduleName){
		$n=$moduleName.'.installed';
		if(isset($this->config->modules[$n]))
			return $this->config->modules[$n];
		else
			return false;
	}
}
