<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
abstract class jInstallerBase{
	protected $path = '';
	function __construct($name){
	}
	abstract function isInstalled();
	abstract function isActivated();
	abstract function install();
	abstract function uninstall();
	abstract function activate();
	abstract function deactivate();
	public function execSQLScript($name, $profile=''){
		$tools = jDb::getTools($profile);
		$p = jDb::getProfile($profile);
		$driver = $p['driver'];
		if($driver == 'pdo'){
			preg_match('/^(\w+)\:.*$/',$p['dsn'], $m);
			$driver = $m[1];
		}
		$tools->execSQLScript($this->path.'install/sql/'.$name.'.'.$driver.'.sql');
	}
	static function copyDirectoryContent($sourcePath, $targetPath){
		jFile::createDir($targetPath);
		$dir = new DirectoryIterator($sourcePath);
		foreach($dir as $dirContent){
			if($dirContent->isFile()){
				copy($dirContent->getPathName(), $targetPath.substr($dirContent->getPathName(), strlen($dirContent->getPath())));
			} else{
				if(!$dirContent->isDot() && $dirContent->isDir()){
					$newTarget = $targetPath.substr($dirContent->getPathName(), strlen($dirContent->getPath()));
					$this->copyDirectoryContent($dirContent->getPathName(),$newTarget);
				}
			}
		}
	}
}