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
abstract class jInstallerBase{
	public $componentName;
	public $name;
	public $version='0';
	public $config;
	public $entryPoint;
	protected $path;
	protected $dbProfile='';
	protected $defaultDbProfile='';
	protected $installWholeApp=false;
	function __construct($componentName,$name,$path,$version,$installWholeApp=false){
		$this->path=$path;
		$this->version=$version;
		$this->name=$name;
		$this->componentName=$componentName;
		$this->installWholeApp=$installWholeApp;
	}
	private $_dbTool=null;
	private $_dbConn=null;
	public function setEntryPoint($ep,$config,$dbProfile){
		$this->config=$config;
		$this->entryPoint=$ep;
		$this->dbProfile=$dbProfile;
		$dbProfilesFile=$config->getValue('dbProfils');
		if($dbProfilesFile=='')
			$dbProfilesFile='dbprofils.ini.php';
		$dbprofiles=parse_ini_file(JELIX_APP_CONFIG_PATH.$dbProfilesFile);
		if(isset($dbprofiles[$dbProfile])&&is_string($dbprofiles[$dbProfile])){
			$this->dbProfile=$dbprofiles[$dbProfile];
		}
		if($this->defaultDbProfile!=''){
			if(isset($dbprofiles[$this->defaultDbProfile])){
				if(is_string($dbprofiles[$this->defaultDbProfile]))
					$this->dbProfile=$dbprofiles[$this->defaultDbProfile];
				else
					$this->dbProfile=$this->defaultDbProfile;
			}
		}
		return "0";
	}
	protected function dbTool(){
		return $this->dbConnection()->tools();
	}
	protected function dbConnection(){
		if(!$this->_dbConn)
			$this->_dbConn=jDb::getConnection($this->dbProfile);
		return $this->_dbConn;
	}
	final protected function execSQLScript($name,$profile=null,$module=null){
		if(!$profile){
			$profile=$this->dbProfile;
			$tools=$this->dbTool();
		}
		else{
			$cnx=jDb::getConnection($profile);
			$tools=$cnx->tools();
		}
		$p=jDb::getProfile($profile);
		$driver=$p['driver'];
		if($driver=='pdo'){
			preg_match('/^(\w+)\:.*$/',$p['dsn'],$m);
			$driver=$m[1];
		}
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
		$file=$path.'install/'.$name.'.'.$driver.'.sql';
		$tools->execSQLScript($path.'install/'.$name.'.'.$driver.'.sql');
	}
	final protected function copyDirectoryContent($relativeSourcePath,$targetPath){
		$this->_copyDirectoryContent($this->path.'install/'.$relativeSourcePath,$targetPath);
	}
	private function _copyDirectoryContent($sourcePath,$targetPath){
		jFile::createDir($targetPath);
		$dir=new DirectoryIterator($sourcePath);
		foreach($dir as $dirContent){
			if($dirContent->isFile()){
				copy($dirContent->getPathName(),$targetPath.substr($dirContent->getPathName(),strlen($dirContent->getPath())));
			}else{
				if(!$dirContent->isDot()&&$dirContent->isDir()){
					$newTarget=$targetPath.substr($dirContent->getPathName(),strlen($dirContent->getPath()));
					$this->_copyDirectoryContent($dirContent->getPathName(),$newTarget);
				}
			}
		}
	}
	final protected function copyFile($relativeSourcePath,$targetPath){
		copy($this->path.'install/'.$relativeSourcePath,$targetPath);
	}
	protected function declareDbProfile($name,$sectionContent=null,$force=true){
		$dbProfilesFile=$this->config->getValue('dbProfils');
		if($dbProfilesFile=='')
			$dbProfilesFile='dbprofils.ini.php';
		$dbprofiles=new jIniFileModifier(JELIX_APP_CONFIG_PATH.$dbProfilesFile);
		if($sectionContent==null){
			if(!$dbprofiles->isSection($name)){
				if($dbprofiles->getValue($name)&&!$force){
					return false;
				}
			}
			else if($force){
				$dbprofiles->removeValue('',$name);
			}
			else{
				return false;
			}
			$default=$dbprofiles->getValue('default');
			if($default){
				$dbprofiles->setValue($name,$default);
			}
			else
				$dbprofiles->setValue($name,'default');
		}
		else{
			if($dbprofiles->getValue($name)!==null){
				if(!$force)
					return false;
				$dbprofiles->removeValue($name);
			}
			if(is_array($sectionContent)){
				foreach($sectionContent as $k=>$v){
					$dbprofiles->setValue($k,$v,$name);
				}
			}
			else{
				$profile=$dbprofiles->getValue($sectionContent);
				if($profile!==null){
					$dbprofiles->setValue($name,$profile);
				}
				else
					$dbprofiles->setValue($name,$sectionContent);
			}
		}
		$dbprofiles->save();
		jDb::clearProfiles();
		return true;
	}
}
