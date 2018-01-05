<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage plugins_cache_file
* @author      Zend Technologies
* @contributor Tahina Ramaroson, Sylvain de Vathaire, Bricet, Laurent Jouanneau
* @copyright  2005-2008 Zend Technologies USA Inc (http://www.zend.com), 2008 Neov, 2011 Laurent Jouanneau
* The implementation of this class is based on Zend Cache Backend File class
* Few lines of code was adapted for Jelix
* @licence  see LICENCE file
*/
class fileCacheDriver implements jICacheDriver{
	const CACHEEXT='.cache';
	protected $_cache_dir;
	protected $_file_locking=true;
	protected $_directory_level=0;
	protected $_directory_umask=0700;
	protected $_file_name_prefix='jelix_cache';
	protected $_cache_file_umask=0600;
	public $profil_name;
	public $enabled=true;
	public $ttl=0;
	public $automatic_cleaning_factor=0;
	public function __construct($params){
		$this->profil_name=$params['_name'];
		if(isset($params['enabled'])){
			$this->enabled=($params['enabled'])?true:false;
		}
		if(isset($params['ttl'])){
			$this->ttl=$params['ttl'];
		}
		$this->_cache_dir=jApp::tempPath('cache/').$this->profil_name.'/';
		if(isset($params['cache_dir'])&&$params['cache_dir']!=''){
			if(is_dir($params['cache_dir'])&&is_writable($params['cache_dir'])){
				$this->_cache_dir=rtrim(realpath($params['cache_dir']),'\\/'). DIRECTORY_SEPARATOR;
			}else{
				throw new jException('jelix~cache.directory.not.writable',$this->profil_name);
			}
		}
		else{
			jFile::createDir($this->_cache_dir);
		}
		if(isset($params['file_locking'])){
			$this->_file_locking=($params['file_locking'])?true:false;
		}
		if(isset($params['automatic_cleaning_factor'])){
			$this->automatic_cleaning_factor=$params['automatic_cleaning_factor'];
		}
		if(isset($params['directory_level'])&&$params['directory_level'] > 0){
			$this->_directory_level=$params['directory_level'];
		}
		if(isset($params['directory_umask'])&&is_string($params['directory_umask'])&&$params['directory_umask']!=''){
			$this->_directory_umask=octdec($params['directory_umask']);
		}
		if(isset($params['file_name_prefix'])){
			$this->_file_name_prefix=$params['file_name_prefix'];
		}
		if(isset($params['cache_file_umask'])&&is_string($params['cache_file_umask'])&&$params['cache_file_umask']!=''){
			$this->_cache_file_umask=octdec($params['cache_file_umask']);
		}
	}
	public function get($key){
		$data=false;
		if(is_array($key)){
			$data=array();
			foreach($key as $value){
				if($this->_isCached($value)){
					$data[$value]=$this->_getFileContent($this->_getCacheFilePath($value));
				}
			}
		}else{
			if($this->_isCached($key)){
				$data=$this->_getFileContent($this->_getCacheFilePath($key));
			}
		}
		return $data;
	}
	public function set($key,$var,$ttl=0){
		$filePath=$this->_getCacheFilePath($key);
		$this->_createDir(dirname($filePath));
		if($this->_setFileContent($filePath,$var)){
			switch($ttl){
				case 0:
					touch($filePath,time()+ 3650*24*3600);
					break;
				default:
					if($ttl<=2592000){
						$ttl+=time();
					}
					touch($filePath,$ttl);
					break;
			}
			return true;
		}
		return false;
	}
	public function delete($key){
		$filePath=$this->_getCacheFilePath($key);
		if(file_exists($filePath)){
			if(!(@unlink($filePath))){
				touch($filePath,strtotime("-1 day"));
				return false;
			}
			return true;
		}
		return false;
	}
	public function increment($key,$var=1){
		if(($oldData=$this->get($key))){
			if(!is_numeric($oldData)){
				return false;
			}
			$data=$oldData + $var;
			if($data<0||$oldData==$data){
				return false;
			}
			return($this->set($key,(int)$data,filemtime($this->_getCacheFilePath($key))))? (int)$data : false;
		}
		return false;
	}
	public function decrement($key,$var=1){
		if($oldData=$this->get($key)){
			if(!is_numeric($oldData)){
				return false;
			}
			$data=$oldData - (int)$var;
			if($data < 0||$oldData==$data){
				return false;
			}
			return($this->set($key,(int)$data,filemtime($this->_getCacheFilePath($key))))? (int)$data : false;
		}
		return false;
	}
	public function replace($key,$var,$ttl=0){
		if(!$this->_isCached($key)){
			return false;
		}
		return $this->set($key,$var,$ttl);
	}
	public function garbage(){
		$this->_removeDir($this->_cache_dir,false,false);
		return true;
	}
	public function flush(){
		$this->_removeDir($this->_cache_dir,true,false);
		return true;
	}
	protected function _isCached($key){
		$filePath=$this->_getCacheFilePath($key);
		if(!file_exists($filePath))
			return false;
		if(version_compare(PHP_VERSION,'5.3.0')>=0)
			clearstatcache(false,$filePath);
		else
			clearstatcache();
		return(filemtime($filePath)> time()||filemtime($filePath)==0)&&is_readable($filePath);
	}
	protected function _getFileContent($filePath){
		if(!is_file($filePath)){
			return null;
		}
		$f=@fopen($filePath,'rb');
		if(!$f){
			return null;
		}
		if($this->_file_locking){
			@flock($f,LOCK_SH);
		}
		$content=stream_get_contents($f);
		if($this->_file_locking){
			@flock($f,LOCK_UN);
		}
		@fclose($f);
		try{
			$content=unserialize($content);
		}catch(Exception $e){
			throw new jException('jelix~cache.error.unserialize.data',array($this->profil_name,$e->getMessage()));
		}
		return $content;
	}
	protected function _setFileContent($filePath,$dataToWrite){
		try{
			$dataToWrite=serialize($dataToWrite);
		}catch(Exception $e){
			throw new jException('jelix~cache.error.serialize.data',array($this->profil_name,$e->getMessage()));
		}
		$f=@fopen($filePath,'wb+');
		if(!$f){
			return false;
		}
		if($this->_file_locking){
			@flock($f,LOCK_EX);
		}
		@fwrite($f,$dataToWrite);
		if($this->_file_locking){
			@flock($f,LOCK_UN);
		}
		@fclose($f);
		@chmod($filePath,$this->_cache_file_umask);
		return true;
	}
	protected function _createDir($dir){
		if(!file_exists($dir)){
			$this->_createDir(dirname($dir));
			@mkdir($dir,$this->_directory_umask);
			@chmod($dir,$this->_directory_umask);
		}
	}
	protected function _getCacheFilePath($key){
		$path=$this->_getPath($key);
		$fileName=$this->_file_name_prefix."___".$key.self::CACHEEXT;
		return $path . $fileName;
	}
	protected function _getPath($key){
		$path=$this->_cache_dir;
		$prefix=$this->_file_name_prefix;
		if($this->_directory_level>0){
			$hash=md5($key);
			for($i=0;$i<$this->_directory_level;$i++){
				$path=$path.$prefix.'__'.substr($hash,0,$i + 1). DIRECTORY_SEPARATOR;
			}
		}
		return $path;
	}
	protected function _removeDir($dir,$all=true,$deleteParent=true){
		if(file_exists($dir)&&$handle=opendir($dir)){
			while(false!==($file=readdir($handle))){
				if($file!='.'&&$file!='..'){
					$f=$dir.'/'.$file;
					if(is_file($f)){
						if($all){
							@unlink($f);
						}else{
							if(version_compare(PHP_VERSION,'5.3.0')>=0)
								clearstatcache(false,$f);
							else
								clearstatcache();
							if(time()> filemtime($f)&&filemtime($f)!=0){
								@unlink($dir.'/'.$file);
							}
						}
					}
					if(is_dir($f)){
						self::_removeDir($f,$all);
					}
				}
			}
			closedir($handle);
			if($deleteParent)
				@rmdir($dir);
		}
	}
}
