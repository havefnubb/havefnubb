<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @copyright   2008-2011 Laurent Jouanneau
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
		$epId=$ep->getEpId();
		if($this->moduleInstaller===null){
			if(!file_exists($this->path.'install/install.php')||$this->moduleInfos[$epId]->skipInstaller){
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
		$this->moduleInstaller->setParameters($this->moduleInfos[$epId]->parameters);
		if($ep->localConfigIni){
			$sparam=$ep->localConfigIni->getValue($this->name.'.installparam','modules');
		}
		else{
			$sparam=$ep->configIni->getValue($this->name.'.installparam','modules');
		}
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
			if(!file_exists($p)||$this->moduleInfos[$epId]->skipInstaller)
				return array();
			$fileList=array();
			if($handle=opendir($p)){
				while(false!==($f=readdir($handle))){
					if(!is_dir($p.$f)){
						if(preg_match('/^upgrade_to_([^_]+)_([^\.]+)\.php$/',$f,$m)){
							$fileList[]=array($f,$m[1],$m[2]);
						}
						else if(preg_match('/^upgrade_([^\.]+)\.php$/',$f,$m)){
							$fileList[]=array($f,'',$m[1]);
						}
					}
				}
				closedir($handle);
			}
			if(!count($fileList)){
				return array();
			}
			foreach($fileList as $fileInfo){
				require_once($p.$fileInfo[0]);
				$cname=$this->name.'ModuleUpgrader_'.$fileInfo[2];
				if(!class_exists($cname))
					throw new jInstallerException("module.upgrader.class.not.found",array($cname,$this->name));
				$upgrader=new $cname($this->name,
										$fileInfo[2],
										$this->path,
										$fileInfo[1],
										false);
				if($fileInfo[1]&&count($upgrader->targetVersions)==0){
					$upgrader->targetVersions=array($fileInfo[1]);
				}
				$this->moduleUpgraders[]=$upgrader;
			}
		}
		$list=array();
		foreach($this->moduleUpgraders as $upgrader){
			$foundVersion='';
			foreach($upgrader->targetVersions as $version){
				if(jVersionComparator::compareVersion($this->moduleInfos[$epId]->version,$version)>=0){
					continue;
				}
				if(jVersionComparator::compareVersion($this->sourceVersion,$version)< 0){
					continue;
				}
				$foundVersion=$version;
				break;
			}
			if(!$foundVersion)
				continue;
			$upgrader->version=$foundVersion;
			if($upgrader->date!=''&&$this->mainInstaller){
				$upgraderDate=$this->_formatDate($upgrader->date);
				$firstVersionDate=$this->_formatDate($this->mainInstaller->installerIni->getValue($this->name.'.firstversion.date',$epId));
				if($firstVersionDate!==null){
					if($firstVersionDate>=$upgraderDate)
						continue;
				}
				$currentVersionDate=$this->_formatDate($this->mainInstaller->installerIni->getValue($this->name.'.version.date',$epId));
				if($currentVersionDate!==null){
					if($currentVersionDate>=$upgraderDate)
						continue;
				}
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
		usort($list,function($upgA,$upgB){
				return jVersionComparator::compareVersion($upgA->version,$upgB->version);
		});
		return $list;
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
	protected function _formatDate($date){
		if($date!==null){
			if(strlen($date)==10)
				$date.=' 00:00';
			else if(strlen($date)> 16){
				$date=substr($date,0,16);
			}
		}
		return $date;
	}
}
