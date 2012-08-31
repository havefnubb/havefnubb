<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @copyright   2009-2011 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
abstract class jInstallerBase{
	public $componentName;
	public $name;
	public $targetVersions=array();
	public $date='';
	public $version='0';
	public $config;
	public $entryPoint;
	protected $path;
	protected $dbProfile='';
	protected $defaultDbProfile='';
	protected $installWholeApp=false;
	protected $parameters=array();
	function __construct($componentName,$name,$path,$version,$installWholeApp=false){
		$this->path=$path;
		$this->version=$version;
		$this->name=$name;
		$this->componentName=$componentName;
		$this->installWholeApp=$installWholeApp;
	}
	function setParameters($parameters){
		$this->parameters=$parameters;
	}
	function getParameter($name){
		if(isset($this->parameters[$name]))
			return $this->parameters[$name];
		else
			return null;
	}
	private $_dbTool=null;
	private $_dbConn=null;
	public function setEntryPoint($ep,$config,$dbProfile,$contexts){
		$this->config=$config;
		$this->entryPoint=$ep;
		$this->contextId=$contexts;
		$this->newContextId=array();
		if($this->defaultDbProfile!=''){
			$this->useDbProfile($this->defaultDbProfile);
		}
		else
			$this->useDbProfile($dbProfile);
	}
	protected function useDbProfile($dbProfile){
		if($dbProfile=='')
			$dbProfile='default';
		$this->dbProfile=$dbProfile;
		if(file_exists(jApp::configPath('profiles.ini.php'))){
			$dbprofiles=parse_ini_file(jApp::configPath('profiles.ini.php'));
			if(isset($dbprofiles['jdb'][$dbProfile]))
				$this->dbProfile=$dbprofiles['jdb'][$dbProfile];
		}
		$this->_dbConn=null;
	}
	protected $contextId=array();
	protected $newContextId=array();
	protected function firstExec($contextId){
		if(in_array($contextId,$this->contextId)){
			return false;
		}
		if(!in_array($contextId,$this->newContextId)){
			$this->newContextId[]=$contextId;
		}
		return true;
	}
	protected function firstDbExec($profile=''){
		if($profile=='')
			$profile=$this->dbProfile;
		return $this->firstExec('db:'.$profile);
	}
	protected function firstConfExec($config=''){
		if($config=='')
			$config=$this->entryPoint->configFile;
		return $this->firstExec('cf:'.$config);
	}
	public function getContexts(){
		return array_unique(array_merge($this->contextId,$this->newContextId));
	}
	protected function dbTool(){
		return $this->dbConnection()->tools();
	}
	protected function dbConnection(){
		if(!$this->_dbConn)
			$this->_dbConn=jDb::getConnection($this->dbProfile);
		return $this->_dbConn;
	}
	protected function getDbType($profile=null){
		if(!$profile)
			$profile=$this->dbProfile;
		$p=jProfiles::get('jdb',$profile);
		$driver=$p['driver'];
		if($driver=='pdo'){
			preg_match('/^(\w+)\:.*$/',$p['dsn'],$m);
			$driver=$m[1];
		}
		return $driver;
	}
	final protected function execSQLScript($name,$module=null,$inTransaction=true){
		$tools=$this->dbTool();
		$driver=$this->getDbType($this->dbProfile);
		if($module){
			$conf=$this->entryPoint->config->_modulesPathList;
			if(!isset($conf[$module])){
				throw new Exception('execSQLScript : invalid module name');
			}
			$path=$conf[$module];
		}
		else{
			$path=$this->path;
		}
		$file=$path.'install/'.$name;
		if(substr($name,-4)!='.sql')
			$file.='.'.$driver.'.sql';
		if($inTransaction)
			$this->dbConnection()->beginTransaction();
		try{
			$tools->execSQLScript($file);
			if($inTransaction){
				$this->dbConnection()->commit();
			}
		}
		catch(Exception $e){
			if($inTransaction)
				$this->dbConnection()->rollback();
			throw $e;
		}
	}
	final protected function copyDirectoryContent($relativeSourcePath,$targetPath,$overwrite=false){
		$targetPath=$this->expandPath($targetPath);
		$this->_copyDirectoryContent($this->path.'install/'.$relativeSourcePath,$targetPath,$overwrite);
	}
	private function _copyDirectoryContent($sourcePath,$targetPath,$overwrite){
		jFile::createDir($targetPath);
		$dir=new DirectoryIterator($sourcePath);
		foreach($dir as $dirContent){
			if($dirContent->isFile()){
				$p=$targetPath.substr($dirContent->getPathName(),strlen($dirContent->getPath()));
				if($overwrite||!file_exists($p))
					copy($dirContent->getPathName(),$p);
			}else{
				if(!$dirContent->isDot()&&$dirContent->isDir()){
					$newTarget=$targetPath.substr($dirContent->getPathName(),strlen($dirContent->getPath()));
					$this->_copyDirectoryContent($dirContent->getPathName(),$newTarget,$overwrite);
				}
			}
		}
	}
	final protected function copyFile($relativeSourcePath,$targetPath,$overwrite=false){
		$targetPath=$this->expandPath($targetPath);
		if(!$overwrite&&file_exists($targetPath))
			return;
		$dir=dirname($targetPath);
		jFile::createDir($dir);
		copy($this->path.'install/'.$relativeSourcePath,$targetPath);
	}
	protected function expandPath($path){
		if(strpos($path,'www:')===0)
			$path=str_replace('www:',jApp::wwwPath(),$path);
		elseif(strpos($path,'jelixwww:')===0){
			$p=$this->config->getValue('jelixWWWPath','urlengine');
			if(substr($p,-1)!='/')
				$p.='/';
			$path=str_replace('jelixwww:',jApp::wwwPath($p),$path);
		}
		elseif(strpos($path,'config:')===0){
			$path=str_replace('config:',jApp::configPath(),$path);
		}
		elseif(strpos($path,'epconfig:')===0){
			$p=dirname(jApp::configPath($this->entryPoint->configFile));
			$path=str_replace('epconfig:',$p.'/',$path);
		}
		return $path;
	}
	protected function declareDbProfile($name,$sectionContent=null,$force=true){
		$profiles=new jIniFileModifier(jApp::configPath('profiles.ini.php'));
		if($sectionContent==null){
			if(!$profiles->isSection('jdb:'.$name)){
				if($profiles->getValue($name,'jdb')&&!$force){
					return false;
				}
			}
			else if($force){
				$profiles->removeValue('','jdb:'.$name);
			}
			else{
				return false;
			}
			$default=$profiles->getValue('default','jdb');
			if($default){
				$profiles->setValue($name,$default,'jdb');
			}
			else
				$profiles->setValue($name,'default','jdb');
		}
		else{
			if($profiles->getValue($name,'jdb')!==null){
				if(!$force)
					return false;
				$profiles->removeValue($name,'jdb');
			}
			if(is_array($sectionContent)){
				foreach($sectionContent as $k=>$v){
					$profiles->setValue($k,$v,'jdb:'.$name);
				}
			}
			else{
				$profile=$profiles->getValue($sectionContent,'jdb');
				if($profile!==null){
					$profiles->setValue($name,$profile,'jdb');
				}
				else
					$profiles->setValue($name,$sectionContent,'jdb');
			}
		}
		$profiles->save();
		jProfiles::clear();
		return true;
	}
}
