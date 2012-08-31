<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage  kvdb
* @author      Zend Technologies
* @contributor Tahina Ramaroson, Sylvain de Vathaire, Laurent Jouanneau
* @copyright  2005-2008 Zend Technologies USA Inc (http://www.zend.com), 2008 Neov, 2010-2011 Laurent Jouanneau
* The implementation of this class is based on Zend Cache Backend File class
* Few lines of code was adapted for Jelix
* @licence  see LICENCE file
*/
class fileKVDriver extends jKVDriver implements jIKVPersistent,jIKVttl{
	protected $_storage_dir;
	protected $_file_locking=true;
	protected $_directory_level=2;
	protected $_directory_umask=0700;
	protected $file_umask=0600;
	public $profil_name;
	public $automatic_cleaning_factor=0;
	public function _connect(){
		if(isset($this->_profile['storage_dir'])&&$this->_profile['storage_dir']!=''){
			$this->_storage_dir=str_replace(array('var:','temp:'),array(jApp::varPath(),jApp::tempPath()),$this->_profile['storage_dir']);
			$this->_storage_dir=rtrim($this->_storage_dir,'\\/'). DIRECTORY_SEPARATOR;
		}
		else
			$this->_storage_dir=jApp::varPath('kvfiles/');
		jFile::createDir($this->_storage_dir);
		if(isset($this->_profile['file_locking'])){
			$this->_file_locking=($this->_profile['file_locking']?true:false);
		}
		if(isset($this->_profile['automatic_cleaning_factor'])){
			$this->automatic_cleaning_factor=$this->_profile['automatic_cleaning_factor'];
		}
		if(isset($this->_profile['directory_level'])&&$this->_profile['directory_level'] > 0){
			$this->_directory_level=$this->_profile['directory_level'];
			if($this->_directory_level > 16)
				$this->_directory_level=16;
		}
		if(isset($this->_profile['directory_umask'])&&is_string($this->_profile['directory_umask'])&&$this->_profile['directory_umask']!=''){
			$this->_directory_umask=octdec($this->_profile['directory_umask']);
		}
		if(isset($this->_profile['file_umask'])&&is_string($this->_profile['file_umask'])&&$this->_profile['file_umask']!=''){
			$this->file_umask=octdec($this->_profile['file_umask']);
		}
	}
	protected function _disconnect(){
	}
	public function get($key){
		$data=null;
		if(is_array($key)){
			$data=array();
			foreach($key as $k){
				if($this->_isStored($k)){
					$data[$k]=$this->_getFileContent($this->_getFilePath($k));
				}
			}
		}
		else{
			if($this->_isStored($key)){
				$data=$this->_getFileContent($this->_getFilePath($key));
			}
		}
		return $data;
	}
	public function set($key,$value){
		$filePath=$this->_getFilePath($key);
		$this->_createDir(dirname($filePath));
		return $this->_setFileContent($filePath,$value,time()+ 3650*24*3600);
	}
	public function insert($key,$value){
		if($this->_isStored($key))
			return false;
		else
			return $this->set($key,$value);
	}
	public function replace($key,$value){
		if(!$this->_isStored($key))
			return false;
		else
			return $this->set($key,$value);
	}
	public function delete($key){
		$filePath=$this->_getFilePath($key);
		if(file_exists($filePath)){
			if(!(@unlink($filePath))){
				touch($filePath,strtotime("-1 day"));
				return false;
			}
			return true;
		}
		return false;
	}
	public function flush(){
		$this->_removeDir($this->_storage_dir,true,false);
		return true;
	}
	public function append($key,$value){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if($this->setWithTtl($key,$oldData.$value,filemtime($this->_getFilePath($key))))
			return $oldData.$value;
		else
			return false;
	}
	public function prepend($key,$value){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if($this->setWithTtl($key,$value.$oldData,filemtime($this->_getFilePath($key))))
			return $value.$oldData;
		else
			return false;
	}
	public function increment($key,$var=1){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if(!is_numeric($oldData)){
			return false;
		}
		$data=$oldData + $var;
		if($data < 0||$oldData==$data){
			return false;
		}
		return($this->setWithTtl($key,(int)$data,filemtime($this->_getFilePath($key))))? (int)$data : false;
	}
	public function decrement($key,$var=1){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if(!is_numeric($oldData)){
			return false;
		}
		$data=$oldData - (int)$var;
		if($data < 0||$oldData==$data){
			return false;
		}
		return($this->setWithTtl($key,(int)$data,filemtime($this->_getFilePath($key))))? (int)$data : false;
	}
	public function sync(){}
	public function setWithTtl($key,$var,$ttl){
		$filePath=$this->_getFilePath($key);
		$this->_createDir(dirname($filePath));
		if($ttl > 0){
			if($ttl<=2592000){
				$ttl+=time();
			}
		}
		else
			$ttl=time()+ 3650*24*3600;
		return $this->_setFileContent($filePath,$var,$ttl);
	}
	public function garbage(){
		$this->_removeDir($this->_storage_dir,false,false);
		return true;
	}
	protected function _isStored($key){
		$filePath=$this->_getFilePath($key);
		if(!file_exists($filePath))
			return false;
		if(version_compare(PHP_VERSION,'5.3.0')>=0)
			clearstatcache(false,$filePath);
		else
			clearstatcache();
		$mt=filemtime($filePath);
		return($mt>=time()||$mt==0)&&is_readable($filePath);
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
		}
		catch(Exception $e){
			throw new jException('jelix~kvstore.error.unserialize.data',array($this->profil_name,$e->getMessage()));
		}
		return $content;
	}
	protected function _setFileContent($filePath,$dataToWrite,$mtime){
		if(is_resource($dataToWrite))
			return false;
		try{
			$dataToWrite=serialize($dataToWrite);
		}
		catch(Exception $e){
			throw new jException('jelix~kvstore.error.serialize.data',array($this->profil_name,$e->getMessage()));
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
		@chmod($filePath,$this->file_umask);
		touch($filePath,$mtime);
		return true;
	}
	protected function _createDir($dir){
		if(!file_exists($dir)){
			$this->_createDir(dirname($dir));
			@mkdir($dir,$this->_directory_umask);
			@chmod($dir,$this->_directory_umask);
		}
	}
	protected $keyPath=array();
	protected function _getFilePath($key){
		if(isset($this->keyPath[$key]))
			return $this->keyPath[$key];
		$hash=md5($key);
		$path=$this->_storage_dir;
		if($this->_directory_level > 0){
			for($i=0;$i < $this->_directory_level;$i++){
				$path.=substr($hash,$i*2,2). DIRECTORY_SEPARATOR;
			}
		}
		if(preg_match("/^([a-zA-Z0-9\._\-]+)/",$key,$m))
			$fileName=$path.$hash."_".$m[1];
		else
			$fileName=$path.$hash;
		$this->keyPath[$key]=$fileName;
		return $fileName;
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
								@unlink($f);
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
