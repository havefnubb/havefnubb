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
	protected $installerContexts=array();
	protected $upgradersContexts=array();
	function __construct($name,$path,$mainInstaller){
		parent::__construct($name,$path,$mainInstaller);
		if($mainInstaller){
			$ini=$mainInstaller->installerIni;
			$contexts=$ini->getValue($this->name.'.contexts','__modules_data');
			if($contexts!==null&&$contexts!==""){
				$this->installerContexts=explode(',',$contexts);
			}
		}
	}
	protected function _setAccess($config){
		$access=$config->getValue($this->name.'.access','modules');
		if($access==0||$access==null){
			$config->setValue($this->name.'.access',2,'modules');
			$config->save();
		}
		else if($access==3){
			$config->setValue($this->name.'.access',1,'modules');
			$config->save();
		}
	}
	function getInstaller($ep,$installWholeApp){
		$this->_setAccess($ep->configIni);
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
		$epId=$ep->getEpId();
		$this->moduleInstaller->setParameters($this->moduleInfos[$epId]->parameters);
		$sparam=$ep->configIni->getValue($this->name.'.installparam','modules');
		if($sparam===null)
			$sparam='';
		$sp=$this->moduleInfos[$epId]->serializeParameters();
		if($sparam!=$sp){
			$ep->configIni->setValue($this->name.'.installparam',$sp,'modules');
		}
		$this->moduleInstaller->setEntryPoint($ep,
											$ep->configIni,
											$this->moduleInfos[$epId]->dbProfile,
											$this->installerContexts);
		return $this->moduleInstaller;
	}
	function getUpgraders($ep){
		$epId=$ep->getEpId();
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
			$upgrader->setParameters($this->moduleInfos[$epId]->parameters);
			$class=get_class($upgrader);
			if(!isset($this->upgradersContexts[$class])){
				$this->upgradersContexts[$class]=array();
			}
			$upgrader->setEntryPoint($ep,
									$ep->configIni,
									$this->moduleInfos[$epId]->dbProfile,
									$this->upgradersContexts[$class]);
			$list[]=$upgrader;
		}
		return $list;
	}
	function sortFileList($fileA,$fileB){
		return jVersionComparator::compareVersion($fileA[1],$fileB[1]);
	}
	public function installFinished($ep){
		$this->installerContexts=$this->moduleInstaller->getContexts();
		if($this->mainInstaller)
			$this->mainInstaller->installerIni->setValue($this->name.'.contexts',implode(',',$this->installerContexts),'__modules_data');
	}
	public function upgradeFinished($ep,$upgrader){
		$class=get_class($upgrader);
		$this->upgradersContexts[$class]=$upgrader->getContexts();
	}
}
