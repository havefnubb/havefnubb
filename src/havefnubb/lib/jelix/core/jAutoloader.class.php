<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage core
* @author     Laurent Jouanneau
* @copyright  2011-2012 Laurent Jouanneau
* @link       http://jelix.org
* @licence    http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jAutoloader{
	protected $nsPaths=array();
	protected $classPaths=array();
	protected $includePaths=array();
	protected $regClassPaths=array();
	public function registerClass($className,$includeFile){
		$this->classPaths[$className]=$includeFile;
	}
	public function registerClassPattern($regExp,$includePath,$extension='.php'){
		$includePath=rtrim(rtrim($includePath,'/'),'\\');
		$this->regClassPaths[$regExp]=array($includePath,$extension);
	}
	public function registerIncludePath($includePath,$extension='.php'){
		$includePath=rtrim(rtrim($includePath,'/'),'\\');
		$this->includePaths[$includePath]=array($extension,true);
	}
	public function registerNamespace($namespace,$includePath,$extension='.php'){
		$includePath=rtrim(rtrim($includePath,'/'),'\\');
		$namespace=trim($namespace,'\\');
		if($namespace==''){
			$this->includePaths[$includePath]=array($extension,true);
		}
		else
			$this->nsPaths[$namespace]=array($includePath,$extension,true);
	}
	public function registerNamespacePathMap($namespace,$includePath,$extension='.php'){
		$includePath=rtrim(rtrim($includePath,'/'),'\\');
		$namespace=trim($namespace,'\\');
		if($namespace=='')
			$this->includePaths[$includePath]=array($extension,false);
		else
			$this->nsPaths[$namespace]=array($includePath,$extension,false);
	}
	public function loadClass($className){
		$path=$this->getPath($className);
		if(is_array($path)){
			foreach($path as $p){
				if(file_exists($p)){
					require($p);
					return true;
				}
			}
		}
		else if($path){
			require($path);
			return true;
		}
		return false;
	}
	protected function getPath($className){
		$className=ltrim($className,'\\');
		if(isset($this->classPaths[$className])){
			return $this->classPaths[$className];
		}
		$lastNsPos=strripos($className,'\\');
		if($lastNsPos!==false){
			$namespace=substr($className,0,$lastNsPos);
			$class=substr($className,$lastNsPos + 1);
		}
		else{
			$namespace='';
			$class=&$className;
		}
		foreach($this->nsPaths as $ns=>$info){
			if(strpos($className,$ns)===0){
				$path='';
				list($incPath,$ext,$psr0)=$info;
				if($lastNsPos!==false){
					if(!$psr0){
						$namespace=substr($namespace,strlen($ns)+1);
					}
					if($namespace){
						$path=str_replace('\\',DIRECTORY_SEPARATOR,$namespace). DIRECTORY_SEPARATOR;
					}
				}
				$fileName=str_replace('_',DIRECTORY_SEPARATOR,$class). $ext;
				return $incPath.DIRECTORY_SEPARATOR.$path.$fileName;
			}
		}
		foreach($this->regClassPaths as $reg=>$info){
			if(preg_match($reg,$className)){
				list($incPath,$ext)=$info;
				return $incPath. DIRECTORY_SEPARATOR .$className.$ext;
			}
		}
		$pathList=array();
		foreach($this->includePaths as $incPath=>$info){
			list($ext,$psr0)=$info;
			if($namespace&&$psr0){
				$path=str_replace('\\',DIRECTORY_SEPARATOR,$namespace). DIRECTORY_SEPARATOR;
			}
			else
				$path='';
			$pathList[]=$incPath.DIRECTORY_SEPARATOR.$path.str_replace('_',DIRECTORY_SEPARATOR,$class). $ext;
		}
		if(count($pathList)){
			return $pathList;
		}
		return '';
	}
}
