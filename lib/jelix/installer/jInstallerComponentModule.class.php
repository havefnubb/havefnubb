<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerComponentModule extends jInstallerComponentBase{
	protected $identityNamespace='http://jelix.org/ns/module/1.0';
	protected $rootName='module';
	protected $identityFile='module.xml';
	protected $moduleInstaller=null;
	protected $moduleUpgraders=null;
	protected $installerSessionsId=array();
	protected $upgradersSessionsId=array();
	function __construct($name,$path,$mainInstaller){
		parent::__construct($name,$path,$mainInstaller);
		if($mainInstaller){
			$ini=$mainInstaller->installerIni;
			foreach($ini->getSectionList()as $section){
				$sessid=$ini->getValue($this->name.'.sessionid',$section);
				if($sessid!==null&&$sessid!==""){
					$this->installerSessionsId=array_merge($this->installerSessionsId,explode(',',$sessid));
				}
			}
		}
	}
	protected function _setAccess($config){
		$access=$config->getValue($this->name.'.access','modules');
		if($access==0||$access==null){
			$config->setValue($this->name.'.access',2,'modules');
			$config->save();
		}
	}
	function getInstaller($config,$epId,$installWholeApp){
		$this->_setAccess($config);
		if($this->moduleInstaller===false){
			return null;
		}
		if($this->moduleInstaller===null){
			if(!file_exists($this->path.'install/install.php')){
				$this->moduleInstaller=false;
				return null;
			}
			require_once($this->path.'install/install.php');
			$cname=$this->name.'ModuleInstaller';
			if(!class_exists($cname))
				throw new jInstallerException("module.installer.class.not.found",array($cname,$this->name));
			$this->moduleInstaller=new $cname($this->name,
												$this->name,
												$this->path,
												$this->sourceVersion,
												$installWholeApp
												);
		}
		$sessionId=$this->moduleInstaller->setEntryPoint($this->moduleInfos[$epId]->entryPoint,
															$config,
															$this->moduleInfos[$epId]->dbProfile);
		if(is_array($sessionId)){
			if(count(array_intersect($sessionId,$this->installerSessionsId))!=0){
				return false;
			}
			else{
				$this->installerSessionsId=array_merge($this->installerSessionsId,$sessionId);
				$sessionId=implode(',',$sessionId);
			}
		}
		else if(in_array($sessionId,$this->installerSessionsId)){
			return false;
		}
		else
			$this->installerSessionsId[]=$sessionId;
		if($this->mainInstaller)
			$this->mainInstaller->installerIni->setValue($this->name.'.sessionid',$sessionId,$epId);
		return $this->moduleInstaller;
	}
	function getUpgraders($config,$epId){
		if($this->moduleUpgraders===null){
			$this->moduleUpgraders=array();
			$p=$this->path.'install/';
			if(!file_exists($p))
				return array();
			$fileList=array();
			if($handle=opendir($p)){
				while(false!==($f=readdir($handle))){
					if(!is_dir($p.$f)&&preg_match('/^upgrade_to_([^_]+)_([^\.]+)\.php$/',$f,$m)){
						$fileList[]=array($f,$m[1],$m[2]);
					}
				}
				closedir($handle);
			}
			if(!count($fileList)){
				return array();
			}
			usort($fileList,array($this,'sortFileList'));
			foreach($fileList as $fileInfo){
				require_once($p.$fileInfo[0]);
				$cname=$this->name.'ModuleUpgrader_'.$fileInfo[2];
				if(!class_exists($cname))
					throw new jInstallerException("module.upgrader.class.not.found",array($cname,$this->name));
				$this->moduleUpgraders[]=new $cname($this->name,
													$fileInfo[2],
													$this->path,
													$fileInfo[1],
													false);
			}
		}
		$list=array();
		foreach($this->moduleUpgraders as $upgrader){
			if(jVersionComparator::compareVersion($this->moduleInfos[$epId]->version,$upgrader->version)>=0){
				continue;
			}
			$class=get_class($upgrader);
			$sessionId=$upgrader->setEntryPoint($this->moduleInfos[$epId]->entryPoint,
												$config,
												$this->moduleInfos[$epId]->dbProfile);
			if(!isset($this->upgradersSessionsId[$class])){
				$this->upgradersSessionsId[$class]=array();
			}
			if(is_array($sessionId)){
				if(count(array_intersect($sessionId,$this->upgradersSessionsId[$class]))==0){
					$this->upgradersSessionsId[$class]=array_merge($this->upgradersSessionsId[$class],$sessionId);
					$list[]=$upgrader;
				}
			}
			else if(!in_array($sessionId,$this->upgradersSessionsId[$class])){
				$this->upgradersSessionsId[$class][]=$sessionId;
				$list[]=$upgrader;
			}
		}
		return $list;
	}
	function sortFileList($fileA,$fileB){
		return jVersionComparator::compareVersion($fileA[1],$fileB[1]);
	}
}
