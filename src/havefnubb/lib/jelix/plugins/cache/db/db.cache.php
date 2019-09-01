<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage cache_plugin
* @author     Tahina Ramaroson
* @contributor Sylvain de Vathaire, Laurent Jouanneau
* @copyright  2009 Neov, 2009-2017 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class dbCacheDriver implements jICacheDriver{
	protected $_dao='jelix~jcache';
	protected $_dbprofile='';
	public $profil_name;
	public $enabled=true;
	public $ttl=0;
	public $automatic_cleaning_factor=0;
	protected $base64encoding=false;
	public function __construct($params){
		$this->profil_name=$params['_name'];
		if(isset($params['enabled'])){
			$this->enabled=($params['enabled']?true:false);
		}
		if(isset($params['ttl'])){
			$this->ttl=$params['ttl'];
		}
		if(isset($params['dao'])){
			$this->_dao=$params['dao'];
		}
		if(isset($params['dbprofile'])){
			$this->_dbprofile=$params['dbprofile'];
		}
		if(isset($params['automatic_cleaning_factor'])){
			$this->automatic_cleaning_factor=$params['automatic_cleaning_factor'];
		}
		if(isset($params['base64encoding'])&&$params['base64encoding']){
			$this->base64encoding=true;
		}
	}
	public function get($key){
		$dao=jDao::get($this->_dao,$this->_dbprofile);
		if(is_array($key)){
			if(($rs=$dao->getDataList($key))===FALSE){
				return false;
			}
			$data=array();
			foreach($rs as $cache){
				if(is_null($cache->date)||(strtotime($cache->date)> time())){
					try{
						$val=$this->base64encoding?base64_decode($cache->data):$cache->data;
						$data[$cache->key]=unserialize($val);
					}catch(Exception $e){
						throw new jException('jelix~cache.error.unserialize.data',array($this->profil_name,$e->getMessage()));
					}
				}
			}
			return $data;
		}
		else{
			$rec=$dao->getData($key);
			if($rec){
				try{
					$val=$this->base64encoding?base64_decode($rec->data):$rec->data;
					$data=unserialize($val);
				}catch(Exception $e){
					throw new jException('jelix~cache.error.unserialize.data',array($this->profil_name,$e->getMessage()));
				}
				return $data;
			}else{
				return false;
			}
		}
	}
	public function set($key,$var,$ttl=0){
		try{
			$var=serialize($var);
			if($this->base64encoding){
				$var=base64_encode($var);
			}
		}
		catch(Exception $e){
			throw new jException('jelix~cache.error.serialize.data',array($this->profil_name,$e->getMessage()));
		}
		$dao=jDao::get($this->_dao,$this->_dbprofile);
		switch($ttl){
			case -1:
				$date=-1;
				$n=$dao->updateData($key,$var);
				break;
			case 0:
				$date=null;
				$n=$dao->updateFullData($key,$var,$date);
				break;
			default:
				if($ttl<=2592000){
					$ttl+=time();
				}
				$date=date("Y-m-d H:i:s",$ttl);
				$n=$dao->updateFullData($key,$var,$date);
				break;
		}
		if($n==0){
			$cache=jDao::createRecord($this->_dao,$this->_dbprofile);
			$cache->key=$key;
			$cache->data=$var;
			$cache->date=$date;
			return !($dao->insert($cache)?false:true);
		}
		return true;
	}
	public function delete($key){
		return (bool)(jDao::get($this->_dao,$this->_dbprofile)->delete($key));
	}
	public function increment($key,$var=1){
		if($oldData=$this->get($key)){
			if(!is_numeric($oldData)||!is_numeric($var)){
				return false;
			}
			$data=$oldData + $var;
			if($data < 0||$oldData==$data){
				return false;
			}
			return($this->set($key,(int)$data,-1)? (int)$data : false);
		}
		return false;
	}
	public function decrement($key,$var=1){
		if(($oldData=$this->get($key))){
			if(!is_numeric($oldData)||!is_numeric($var)){
				return false;
			}
			$data=$oldData - (int)$var;
			if($data < 0||$oldData==$data){
				return false;
			}
			return($this->set($key,(int)$data,-1)? (int)$data : false);
		}
		return false;
	}
	public function replace($key,$var,$ttl=0){
		$dao=jDao::get($this->_dao,$this->_dbprofile);
		if(!$dao->get($key)){
			return false;
		}
		return $this->set($key,$var,$ttl);
	}
	public function garbage(){
		jDao::get($this->_dao,$this->_dbprofile)->garbage();
		return true;
	}
	public function flush(){
		jDao::get($this->_dao,$this->_dbprofile)->flush();
		return true;
	}
}
