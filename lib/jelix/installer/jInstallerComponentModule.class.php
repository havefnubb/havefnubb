<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @copyright   2008-2018 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jInstallerComponentModule extends jInstallerComponentBase{
	protected $identityNamespace='http://jelix.org/ns/module/1.0';
	protected $rootName='module';
	protected $identityFile='module.xml';
	protected $moduleInstaller=null;
	protected $moduleUpgraders=null;
	protected $moduleMainUpgrader=null;
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
	protected function _setAccess($config,$localconfig){
		$localAccess=$localconfig->getValue($this->name.'.access','modules');
		$access=$config->getValue($this->name.'.access','modules');
		$config=$config->getOverrider();
		if($localAccess==2){
			$config->removeValue($this->name . '.access','modules');
			$config->save();
		}
		else if($access==0||$access==null){
			$config->setValue($this->name.'.access',2,'modules');
			$config->save();
		}
		else if($access==3){
			$config->setValue($this->name.'.access',1,'modules');
			$config->save();
		}
	}
	function getInstaller($ep,$installWholeApp){
		$this->_setAccess($ep->configIni,$ep->localConfigIni->getMaster());
		if($this->moduleInstaller===false){
			return null;
		}
		$epId=$ep->getEpId();
		if($this->moduleInstaller===null){
			if($this->moduleInfos[$epId]->skipInstaller){
				$this->moduleInstaller=false;
				return null;
			}
			$script='install_1_6.php';
			if(!file_exists($this->path.'install/'.$script)){
				$script='install.php';
				if(!file_exists($this->path.'install/'.$script)){
					$this->moduleInstaller=false;
					return null;
				}
			}
			require_once($this->path.'install/'.$script);
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
		if($this->moduleMainUpgrader===null){
			if(file_exists($this->path . 'install/upgrade_1_6.php')){
				$file=$this->path . 'install/upgrade_1_6.php';
			}
			else if(file_exists($this->path . 'install/upgrade.php')){
				$file=$this->path . 'install/upgrade.php';
			}
			else{
				$file='';
			}
			if($file==''||$this->moduleInfos[$epId]->skipInstaller){
				$this->moduleMainUpgrader=false;
			}
			else{
				require_once($file);
				$cname=$this->name.'ModuleUpgrader';
				if(!class_exists($cname)){
					throw new Exception("module.upgrader.class.not.found",array($cname,$this->name));
				}
				$this->moduleMainUpgrader=new $cname($this->name,
					$this->name,
					$this->path,
					$this->moduleInfos[$epId]->version,
					false
				);
				$this->moduleMainUpgrader->targetVersions=array($this->moduleInfos[$epId]->version);
			}
		}
		if($this->moduleUpgraders===null){
			$this->moduleUpgraders=array();
			$p=$this->path.'install/';
			if(!file_exists($p)||$this->moduleInfos[$epId]->skipInstaller){
				return array();
			}
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
				if(count($upgrader->targetVersions)==0){
					throw new jInstallerException("module.upgrader.missing.version",array($fileInfo[0],$this->name));
				}
				$this->moduleUpgraders[]=$upgrader;
			}
		}
		if((count($this->moduleUpgraders)||$this->moduleMainUpgrader)&&$this->moduleInfos[$epId]->version==''){
			throw new jInstallerException("installer.ini.missing.version",array($this->name));
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
		if($this->moduleMainUpgrader&&jVersionComparator::compareVersion($this->moduleInfos[$epId]->version,$this->sourceVersion)< 0){
			$list[]=$this->moduleMainUpgrader;
			$class=$this->name.'ModuleUpgrader';
			if(!isset($this->upgradersContexts[$class])){
				$this->upgradersContexts[$class]=array();
			}
			$this->moduleMainUpgrader->setEntryPoint($ep,
				$ep->configIni,
				$this->moduleInfos[$epId]->dbProfile,
				$this->upgradersContexts[$class]);
		}
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
