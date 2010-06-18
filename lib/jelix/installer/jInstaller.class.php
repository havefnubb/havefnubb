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
require_once(JELIX_LIB_PATH.'installer/jIInstallReporter.iface.php');
require_once(JELIX_LIB_PATH.'installer/jIInstallerComponent.iface.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerException.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerBase.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerModule.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerModuleInfos.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerComponentBase.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerComponentModule.class.php');
require_once(JELIX_LIB_PATH.'installer/jInstallerEntryPoint.class.php');
require_once(JELIX_LIB_PATH.'core/jConfigCompiler.class.php');
require_once(JELIX_LIB_PATH.'utils/jIniFile.class.php');
require_once(JELIX_LIB_PATH.'utils/jIniFileModifier.class.php');
require_once(JELIX_LIB_PATH.'utils/jIniMultiFilesModifier.class.php');
require(JELIX_LIB_PATH.'installer/jInstallerMessageProvider.class.php');
class textInstallReporter implements jIInstallReporter{
	protected $level;
	function __construct($level='notice'){
		$this->level=$level;
	}
	function start(){
		if($this->level=='notice')
			echo "Installation start..\n";
	}
	function message($message,$type=''){
		if(($type=='error'&&$this->level!='')
			||($type=='warning'&&$this->level!='notice'&&$this->level!='')
			||(($type=='notice'||$type=='')&&$this->level=='notice'))
		echo($type!=''?'['.$type.'] ':'').$message."\n";
	}
	function end($results){
		if($this->level=='notice')
			echo "Installation ended.\n";
	}
}
class ghostInstallReporter implements jIInstallReporter{
	function start(){
	}
	function message($message,$type=''){
	}
	function end($results){
	}
}
class jInstaller{
	const STATUS_UNINSTALLED=0;
	const STATUS_INSTALLED=1;
	const ACCESS_FORBIDDEN=0;
	const ACCESS_PRIVATE=1;
	const ACCESS_PUBLIC=2;
	const INSTALL_ERROR_MISSING_DEPENDENCIES=1;
	const INSTALL_ERROR_CIRCULAR_DEPENDENCY=2;
	const FLAG_INSTALL_MODULE=1;
	const FLAG_UPGRADE_MODULE=2;
	const FLAG_ALL=3;
	const FLAG_MIGRATION_11X=66;
	public $installerIni=null;
	protected $entryPoints=array();
	protected $epId=array();
	protected $modules=array();
	protected $allModules=array();
	public $reporter;
	public $messages;
	public $nbError=0;
	public $nbOk=0;
	public $nbWarning=0;
	public $nbNotice=0;
	function __construct($reporter,$lang=''){
		$this->reporter=$reporter;
		$this->messages=new jInstallerMessageProvider($lang);
		$this->installerIni=$this->getInstallerIni();
		$this->readEntryPointData(simplexml_load_file(JELIX_APP_PATH.'project.xml'));
		$this->installerIni->save();
	}
	protected function getInstallerIni(){
		if(!file_exists(JELIX_APP_CONFIG_PATH.'installer.ini.php'))
			file_put_contents(JELIX_APP_CONFIG_PATH.'installer.ini.php',";<?php die(''); ?>
; for security reasons , don't remove or modify the first line
; don't modify this file if you don't know what you do. it is generated automatically by jInstaller

");
		return new jIniFileModifier(JELIX_APP_CONFIG_PATH.'installer.ini.php');
	}
	protected function readEntryPointData($xml){
		$configFileList=array();
		foreach($xml->entrypoints->entry as $entrypoint){
			$file=(string)$entrypoint['file'];
			$configFile=(string)$entrypoint['config'];
			if(isset($entrypoint['type'])){
				$type=(string)$entrypoint['type'];
			}
			else
				$type="classic";
			if(isset($configFileList[$configFile]))
				continue;
			$configFileList[$configFile]=true;
			$ep=$this->getEntryPointObject($configFile,$file,$type);
			$epId=$ep->getEpId();
			$this->epId[$file]=$epId;
			$this->entryPoints[$epId]=$ep;
			$this->modules[$epId]=array();
			foreach($ep->getModulesList()as $name=>$path){
				$module=$ep->getModule($name);
				$this->installerIni->setValue($name.'.installed',$module->isInstalled,$epId);
				$this->installerIni->setValue($name.'.version',$module->version,$epId);
				$this->installerIni->setValue($name.'.sessionid',$module->sessionId,$epId);
				if(!isset($this->allModules[$path])){
					$this->allModules[$path]=$this->getComponentModule($name,$path,$this);
				}
				$m=$this->allModules[$path];
				$m->addModuleInfos($module);
				$this->modules[$epId][$name]=$m;
			}
		}
	}
	protected function getEntryPointObject($configFile,$file,$type){
		return new jInstallerEntryPoint($configFile,$file,$type);
	}
	protected function getComponentModule($name,$path,$installer){
		return new jInstallerComponentModule($name,$path,$installer);
	}
	public function getEntryPoint($epId){
		return $this->entryPoints[$epId];
	}
	public function forceModuleVersion($moduleName,$version){
		foreach(array_keys($this->entryPoints)as $epId){
			$modules=array();
			if(isset($this->modules[$epId][$moduleName])){
				$this->modules[$epId][$moduleName]->setInstalledVersion($epId,$version);
			}
		}
	}
	public function setModuleParameters($moduleName,$parameters,$entrypoint=null){
		if($entrypoint!==null){
			if(!isset($this->epId[$entrypoint]))
				return;
			$epId=$this->epId[$entrypoint];
			if(isset($this->entryPoints[$epId])&&isset($this->modules[$epId][$moduleName])){
				$this->modules[$epId][$moduleName]->setInstallParameters($epId,$parameters);
			}
		}
		else{
			foreach(array_keys($this->entryPoints)as $epId){
				$modules=array();
				if(isset($this->modules[$epId][$moduleName])){
					$this->modules[$epId][$moduleName]->setInstallParameters($epId,$parameters);
				}
			}
		}
	}
	public function installApplication($flags=false){
		if($flags===false)
			$flags=self::FLAG_ALL;
		$this->startMessage();
		$result=true;
		foreach(array_keys($this->entryPoints)as $epId){
			$modules=array();
			foreach($this->modules[$epId] as $name=>$module){
				if($module->getAccessLevel($epId)==0)
					continue;
				$modules[$name]=$module;
			}
			$result=$result & $this->_installModules($modules,$epId,true,$flags);
			if(!$result)
				break;
		}
		$this->installerIni->save();
		$this->endMessage();
		return $result;
	}
	public function installEntryPoint($entrypoint){
		$this->startMessage();
		if(!isset($this->epId[$entrypoint])){
			throw new Exception("unknown entry point");
		}
		$epId=$this->epId[$entrypoint];
		$modules=array();
		foreach($this->modules[$epId] as $name=>$module){
			if($module->getAccessLevel($epId)==0)
				continue;
			$modules[$name]=$module;
		}
		$result=$this->_installModules($modules,$epId,true);
		$this->installerIni->save();
		$this->endMessage();
		return $result;
	}
	public function installModules($modulesList,$entrypoint=null){
		$this->startMessage();
		$entryPointList=array();
		if($entrypoint==null){
			$entryPointList=array_keys($this->entryPoints);
		}
		else if(isset($this->epId[$entrypoint])){
			$entryPointList=array($this->epId[$entrypoint]);
		}
		else{
			throw new Exception("unknown entry point");
		}
		foreach($entryPointList as $epId){
			$allModules=&$this->modules[$epId];
			$modules=array();
			array_unshift($modulesList,'jelix');
			foreach($modulesList as $name){
				if(!isset($allModules[$name])){
					$this->error('module.unknown',$name);
				}
				else
					$modules[]=$allModules[$name];
			}
			$result=$this->_installModules($modules,$epId,false);
			if(!$result)
				break;
			$this->installerIni->save();
		}
		$this->endMessage();
		return $result;
	}
	protected function _installModules(&$modules,$epId,$installWholeApp,$flags=3){
		$this->notice('install.entrypoint.start',$epId);
		$ep=$this->entryPoints[$epId];
		$GLOBALS['gJConfig']=$ep->config;
		$epConfig=new jIniMultiFilesModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php',
												JELIX_APP_CONFIG_PATH.$ep->configFile);
		$result=$this->checkDependencies($modules,$epId);
		if(!$result){
			$this->error('install.bad.dependencies');
			$this->ok('install.entrypoint.bad.end',$epId);
			return false;
		}
		$this->ok('install.dependencies.ok');
		$componentsToInstall=array();
		foreach($this->_componentsToInstall as $item){
			list($component,$toInstall)=$item;
			try{
				if($flags==self::FLAG_MIGRATION_11X){
					$this->installerIni->setValue($component->getName().'.installed',
													1,$epId);
					$this->installerIni->setValue($component->getName().'.version',
													$component->getSourceVersion(),$epId);
					$upgraders=$component->getUpgraders($epConfig,$epId);
					if(count($upgraders)==0){
						$this->ok('install.module.installed',$component->getName());
						continue;
					}
					foreach($upgraders as $upgrader){
						$upgrader->preInstall();
					}
					$componentsToInstall[]=array($upgraders,$component,false);
				}
				else if($toInstall){
					$installer=$component->getInstaller($epConfig,$epId,$installWholeApp);
					if($installer===null||$installer===false){
						$this->installerIni->setValue($component->getName().'.installed',
														1,$epId);
						$this->installerIni->setValue($component->getName().'.version',
														$component->getSourceVersion(),$epId);
						$this->ok('install.module.installed',$component->getName());
						continue;
					}
					$componentsToInstall[]=array($installer,$component,$toInstall);
					if($flags & self::FLAG_INSTALL_MODULE)
						$installer->preInstall();
				}
				else{
					$upgraders=$component->getUpgraders($epConfig,$epId);
					if(count($upgraders)==0){
						$this->installerIni->setValue($component->getName().'.version',
													$component->getSourceVersion(),$epId);
						$this->ok('install.module.upgraded',
								array($component->getName(),$component->getSourceVersion()));
						continue;
					}
					if($flags & self::FLAG_UPGRADE_MODULE){
						foreach($upgraders as $upgrader){
							$upgrader->preInstall();
						}
					}
					$componentsToInstall[]=array($upgraders,$component,$toInstall);
				}
			}catch(jInstallerException $e){
				$result=false;
				$this->error($e->getLocaleKey(),$e->getLocaleParameters());
			}catch(Exception $e){
				$result=false;
				$this->error('install.module.error',array($component->getName(),$e->getMessage()));
			}
		}
		if(!$result){
			$this->warning('install.entrypoint.bad.end',$epId);
			return false;
		}
		$installedModules=array();
		try{
			foreach($componentsToInstall as $item){
				list($installer,$component,$toInstall)=$item;
				if($toInstall){
					if($installer&&($flags & self::FLAG_INSTALL_MODULE))
						$installer->install();
					$this->installerIni->setValue($component->getName().'.installed',
													1,$epId);
					$this->installerIni->setValue($component->getName().'.version',
													$component->getSourceVersion(),$epId);
					$this->ok('install.module.installed',$component->getName());
					$installedModules[]=array($installer,$component,true);
				}
				else{
					$lastversion='';
					foreach($installer as $upgrader){
						if($flags & self::FLAG_UPGRADE_MODULE)
							$upgrader->install();
						$this->installerIni->setValue($component->getName().'.version',
													$upgrader->version,$epId);
						$this->ok('install.module.upgraded',
								array($component->getName(),$upgrader->version));
						$lastversion=$upgrader->version;
					}
					if($lastversion!=$component->getSourceVersion()){
						$this->installerIni->setValue($component->getName().'.version',
													$component->getSourceVersion(),$epId);
						$this->ok('install.module.upgraded',
								array($component->getName(),$component->getSourceVersion()));
					}
					$installedModules[]=array($installer,$component,false);
				}
				$epConfig->save();
				$GLOBALS['gJConfig']=$ep->config=
					jConfigCompiler::read($ep->configFile,true,
										$ep->isCliScript,
										$ep->scriptName);
			}
		}catch(jInstallerException $e){
			$result=false;
			$this->error($e->getLocaleKey(),$e->getLocaleParameters());
		}catch(Exception $e){
			$result=false;
			$this->error('install.module.error',array($component->getName(),$e->getMessage()));
		}
		if(!$result){
			$this->warning('install.entrypoint.bad.end',$epId);
			return false;
		}
		foreach($installedModules as $item){
			try{
				list($installer,$component,$toInstall)=$item;
				if($toInstall){
					if($installer&&($flags & self::FLAG_INSTALL_MODULE))
						$installer->postInstall();
				}
				else if($flags & self::FLAG_UPGRADE_MODULE){
					foreach($installer as $upgrader){
						$upgrader->postInstall();
					}
				}
				$epConfig->save();
				$GLOBALS['gJConfig']=$ep->config=
					jConfigCompiler::read($ep->configFile,true,
										$ep->isCliScript,
										$ep->scriptName);
			}catch(jInstallerException $e){
				$result=false;
				$this->error($e->getLocaleKey(),$e->getLocaleParameters());
			}catch(Exception $e){
				$result=false;
				$this->error('install.module.error',array($component->getName(),$e->getMessage()));
			}
		}
		$this->ok('install.entrypoint.end',$epId);
		return $result;
	}
	protected $_componentsToInstall=array();
	protected $_checkedComponents=array();
	protected $_checkedCircularDependency=array();
	protected function checkDependencies($list,$epId){
		$this->_checkedComponents=array();
		$this->_componentsToInstall=array();
		$result=true;
		foreach($list as $component){
			$this->_checkedCircularDependency=array();
			if(!isset($this->_checkedComponents[$component->getName()])){
				try{
					$component->init();
					$this->_checkDependencies($component,$epId);
					if(!$component->isInstalled($epId)){
						$this->_componentsToInstall[]=array($component,true);
					}
					else if(!$component->isUpgraded($epId)){
						$this->_componentsToInstall[]=array($component,false);
					}
				}catch(jInstallerException $e){
					$result=false;
					$this->error($e->getLocaleKey(),$e->getLocaleParameters());
				}catch(Exception $e){
					$result=false;
					$this->error($e->getMessage(),null,true);
				}
			}
		}
		return $result;
	}
	protected function _checkDependencies($component,$epId){
		if(isset($this->_checkedCircularDependency[$component->getName()])){
			$component->inError=self::INSTALL_ERROR_CIRCULAR_DEPENDENCY;
			throw new jInstallerException('module.circular.dependency',$component->getName());
		}
		$this->_checkedCircularDependency[$component->getName()]=true;
		$compNeeded='';
		foreach($component->dependencies as $compInfo){
			if($compInfo['type']!='module')
				continue;
			$name=$compInfo['name'];
			$comp=$this->modules[$epId][$name];
			if(!$comp)
				$compNeeded.=$name.', ';
			else{
				if(!isset($this->_checkedComponents[$comp->getName()])){
					$comp->init();
				}
				if(!$comp->checkVersion($compInfo['minversion'],$compInfo['maxversion'])){
					if($name=='jelix'){
						$args=$component->getJelixVersion();
						array_unshift($args,$component->getName());
						throw new jInstallerException('module.bad.jelix.version',$args);
					}
					else
						throw new jInstallerException('module.bad.dependency.version',array($component->getName(),$comp->getName(),$compInfo['minversion'],$compInfo['maxversion']));
				}
				if(!isset($this->_checkedComponents[$comp->getName()])){
					$this->_checkDependencies($comp,$epId);
					if(!$comp->isInstalled($epId)){
						$this->_componentsToInstall[]=array($comp,true);
					}
					else if(!$comp->isUpgraded($epId)){
						$this->_componentsToInstall[]=array($comp,false);
					}
				}
			}
		}
		$this->_checkedComponents[$component->getName()]=true;
		unset($this->_checkedCircularDependency[$component->getName()]);
		if($compNeeded){
			$component->inError=self::INSTALL_ERROR_MISSING_DEPENDENCIES;
			throw new jInstallerException('module.needed',array($component->getName(),$compNeeded));
		}
	}
	protected function startMessage(){
		$this->nbError=0;
		$this->nbOk=0;
		$this->nbWarning=0;
		$this->nbNotice=0;
		$this->reporter->start();
	}
	protected function endMessage(){
		$this->reporter->end(array('error'=>$this->nbError,'warning'=>$this->nbWarning,'ok'=>$this->nbOk,'notice'=>$this->nbNotice));
	}
	protected function error($msg,$params=null,$fullString=false){
		if($this->reporter){
			if(!$fullString)
				$msg=$this->messages->get($msg,$params);
			$this->reporter->message($msg,'error');
		}
		$this->nbError ++;
	}
	protected function ok($msg,$params=null,$fullString=false){
		if($this->reporter){
			if(!$fullString)
				$msg=$this->messages->get($msg,$params);
			$this->reporter->message($msg,'');
		}
		$this->nbOk ++;
	}
	protected function warning($msg,$params=null,$fullString=false){
		if($this->reporter){
			if(!$fullString)
				$msg=$this->messages->get($msg,$params);
			$this->reporter->message($msg,'warning');
		}
		$this->nbWarning ++;
	}
	protected function notice($msg,$params=null,$fullString=false){
		if($this->reporter){
			if(!$fullString)
				$msg=$this->messages->get($msg,$params);
			$this->reporter->message($msg,'notice');
		}
		$this->nbNotice ++;
	}
}
