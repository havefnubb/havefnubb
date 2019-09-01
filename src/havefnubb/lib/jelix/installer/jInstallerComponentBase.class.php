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
require_once(JELIX_LIB_UTILS_PATH."jVersionComparator.class.php");
abstract class jInstallerComponentBase{
	protected $name='';
	protected $path='';
	protected $sourceVersion='';
	protected $sourceDate='';
	protected $identityNamespace='';
	protected $rootName='';
	protected $identityFile='';
	protected $mainInstaller=null;
	public $dependencies=array();
	protected $jelixMinVersion='*';
	protected $jelixMaxVersion='*';
	public $inError=0;
	protected $moduleInfos=array();
	function __construct($name,$path,$mainInstaller){
		$this->path=$path;
		$this->name=$name;
		$this->mainInstaller=$mainInstaller;
	}
	public function getName(){return $this->name;}
	public function getPath(){return $this->path;}
	public function getSourceVersion(){return $this->sourceVersion;}
	public function getSourceDate(){return $this->sourceDate;}
	public function getJelixVersion(){return array($this->jelixMinVersion,$this->jelixMaxVersion);}
	public function addModuleInfos($epId,$module){
		$this->moduleInfos[$epId]=$module;
	}
	public function getAccessLevel($epId){
		return $this->moduleInfos[$epId]->access;
	}
	public function isInstalled($epId){
		return $this->moduleInfos[$epId]->isInstalled;
	}
	public function isUpgraded($epId){
		if(!$this->isInstalled($epId)){
			return false;
		}
		if($this->moduleInfos[$epId]->version==''){
			throw new jInstallerException("installer.ini.missing.version",array($this->name));
		}
		return jVersionComparator::compareVersion($this->sourceVersion,$this->moduleInfos[$epId]->version)==0;
	}
	public function getInstalledVersion($epId){
		return $this->moduleInfos[$epId]->version;
	}
	public function setInstalledVersion($epId,$version){
		$this->moduleInfos[$epId]->version=$version;
	}
	public function setInstallParameters($epId,$parameters){
		$this->moduleInfos[$epId]->parameters=$parameters;
	}
	public function getInstallParameters($epId){
		return $this->moduleInfos[$epId]->parameters;
	}
	abstract function getInstaller($ep,$installWholeApp);
	abstract function getUpgraders($ep);
	public function installFinished($ep){}
	public function upgradeFinished($ep,$upgrader){}
	protected $identityReaded=false;
	public function init(){
		if($this->identityReaded)
			return;
		$this->identityReaded=true;
		$this->readIdentity();
	}
	protected function readIdentity(){
		$xmlDescriptor=new DOMDocument();
		if(!$xmlDescriptor->load($this->path.$this->identityFile)){
			throw new jInstallerException('install.invalid.xml.file',array($this->path.$this->identityFile));
		}
		$root=$xmlDescriptor->documentElement;
		if($root->namespaceURI==$this->identityNamespace){
			$xml=simplexml_import_dom($xmlDescriptor);
			if(!isset($xml->info[0]->version[0])){
				throw new jInstallerException('module.missing.version',array($this->name));
			}
			$this->sourceVersion=(string) $xml->info[0]->version[0];
			if(trim($this->sourceVersion)==''){
				throw new jInstallerException('module.missing.version',array($this->name));
			}
			if(isset($xml->info[0]->version['date']))
				$this->sourceDate=(string) $xml->info[0]->version['date'];
			else
				$this->sourceDate='';
			$this->readDependencies($xml);
		}
	}
	protected function readDependencies($xml){
		$this->dependencies=array();
		if(isset($xml->dependencies)){
			foreach($xml->dependencies->children()as $type=>$dependency){
				if($type!='jelix'&&$type!='module'&&$type!='plugin'){
					continue;
				}
				$minversion=isset($dependency['minversion'])?(string)$dependency['minversion']:'*';
				if(trim($minversion)=='')
					$minversion='*';
				$maxversion=isset($dependency['maxversion'])?(string)$dependency['maxversion']:'*';
				if(trim($maxversion)=='')
					$maxversion='*';
				$name=(string)$dependency['name'];
				if(trim($name)==''&&$type!='jelix')
					throw new Exception('Name is missing in a dependency declaration in module '.$this->name);
				$id=(string)$dependency['id'];
				if($type=='jelix'){
					$this->jelixMinVersion=$minversion;
					$this->jelixMaxVersion=$maxversion;
					if($this->name!='jelix'){
						$this->dependencies[]=array(
							'type'=>'module',
							'id'=>'jelix@jelix.org',
							'name'=>'jelix',
							'minversion'=>$this->jelixMinVersion,
							'maxversion'=>$this->jelixMaxVersion,
							''
						);
					}
				}
				else if($type=='module'){
					$this->dependencies[]=array(
							'type'=>'module',
							'id'=>$id,
							'name'=>$name,
							'minversion'=>$minversion,
							'maxversion'=>$maxversion,
							''
							);
				}
				else if($type=='plugin'){
					$this->dependencies[]=array(
							'type'=>'plugin',
							'id'=>$id,
							'name'=>$name,
							'minversion'=>$minversion,
							'maxversion'=>$maxversion,
							''
							);
				}
			}
		}
	}
	public function checkJelixVersion($jelixVersion){
		return(jVersionComparator::compareVersion($this->jelixMinVersion,$jelixVersion)<=0&&
				jVersionComparator::compareVersion($jelixVersion,$this->jelixMaxVersion)<=0);
	}
	public function checkVersion($min,$max){
		return(jVersionComparator::compareVersion($min,$this->sourceVersion)<=0&&
				jVersionComparator::compareVersion($this->sourceVersion,$max)<=0);
	}
}
