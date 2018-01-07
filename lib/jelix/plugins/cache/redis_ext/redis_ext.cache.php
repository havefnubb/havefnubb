<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  cache
 * @author      Yannick Le Guédart
 * @contributor Laurent Jouanneau
 * @copyright   2009 Yannick Le Guédart, 2010-2017 Laurent Jouanneau
 *
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
class redis_extCacheDriver implements jICacheDriver{
	protected $profileName;
	public $enabled=true;
	public $ttl=0;
	public $automatic_cleaning_factor=0;
	protected $key_prefix='';
	protected $key_prefix_flush_method='direct';
	protected $redis;
	public function __construct($params){
		$this->profileName=$params['_name'];
		if(! isset($params['host'])){
			throw new jException(
				'jelix~cache.error.no.host',$this->profileName);
		}
		if(! isset($params['port'])){
			throw new jException(
				'jelix~cache.error.no.port',$this->profileName);
		}
		if(isset($params['enabled'])){
			$this->enabled=($params['enabled'])?true:false;
		}
		if(isset($params['ttl'])){
			$this->ttl=$params['ttl'];
		}
		if(isset($params['automatic_cleaning_factor'])){
			$this->automatic_cleaning_factor=$params['automatic_cleaning_factor'];
		}
		if(isset($params['key_prefix'])){
			$this->key_prefix=$params['key_prefix'];
		}
		if($this->key_prefix&&isset($params['key_prefix_flush_method'])){
			if(in_array($params['key_prefix_flush_method'],
						array('direct','jcacheredisworker','event'))){
				$this->key_prefix_flush_method=$params['key_prefix_flush_method'];
			}
		}
		$this->redis=new jRedis();
		$this->redis->connect($params['host'],$params['port']);
		if(isset($params['db'])&&intval($params['db'])!=0){
			$this->redis->select($params['db']);
		}
	}
	public function getRedis(){
		return $this->redis;
	}
	public function get($key){
		$used_key=$this->getUsedKey($key);
		if(is_array($key)){
			$res=$this->redis->mGet($used_key);
		}
		else{
			$res=$this->redis->get($used_key);
		}
		if($res===false)
			return false;
		$res=$this->unesc($res);
		if(is_array($key)){
			return array_combine($key,$res);
		}
		else{
			return $res;
		}
	}
	public function set($key,$value,$ttl=0){
		if(is_resource($value)){
			return false;
		}
		$used_key=$this->getUsedKey($key);
		$res=$this->redis->set($used_key,$this->esc($value));
		if($res!==true){
			return false;
		}
		if($ttl===0){
			return true;
		}
		if($ttl!=0&&$ttl > 2592000){
			$ttl-=time();
		}
		if($ttl<=0){
			return true;
		}
		return $this->redis->expire($used_key,$ttl);
	}
	public function delete($key){
		$used_key=$this->getUsedKey($key);
		return($this->redis->delete($used_key)> 0);
	}
	public function increment($key,$incvalue=1){
		$val=$this->get($key);
		if($val===null||!is_numeric($val)||!is_numeric($incvalue)){
			return false;
		}
		$usedkey=$this->getUsedKey($key);
		if(intval($val)==$val){
			if($incvalue!=1){
				return $this->redis->incrBy($usedkey,intval($incvalue));
			}
			return $this->redis->incr($usedkey);
		}
		else{
			$result=intval($val)+intval($incvalue);
			if($this->redis->set($usedkey,$result))
				return $result;
			return false;
		}
	}
	public function decrement($key,$decvalue=1){
		$val=$this->get($key);
		if($val===null||!is_numeric($val)||!is_numeric($decvalue)){
			return false;
		}
		$usedkey=$this->getUsedKey($key);
		if(intval($val)==$val){
			if($decvalue!=1){
				return $this->redis->decrBy($usedkey,intval($decvalue));
			}
			return $this->redis->decr($usedkey);
		}
		else{
			$result=intval($val)-intval($decvalue);
			if($this->redis->set($usedkey,$result))
				return $result;
			return false;
		}
	}
	public function replace($key,$var,$ttl=0){
		$used_key=$this->getUsedKey($key);
		if(!$this->redis->exists($used_key)){
			return false;
		}
		return $this->set($key,$var,$ttl);
	}
	public function garbage(){
		return true;
	}
	public function flush(){
		if(!$this->key_prefix){
			return $this->redis->flushDb();
		}
		switch($this->key_prefix_flush_method){
			case 'direct':
				$this->redis->flushByPrefix($this->key_prefix);
				return true;
			case 'event':
				jEvent::notify('jCacheRedisFlushKeyPrefix',array('prefix'=>$this->key_prefix,
																'profile'=>$this->profileName));
				return true;
			case 'jcacheredisworker':
				$this->redis->rPush('jcacheredisdelkeys',$this->key_prefix);
				return true;
		}
		return false;
	}
	protected function getUsedKey($key){
		if($this->key_prefix==''){
			return $key;
		}
		$prefix=$this->key_prefix;
		if(is_array($key)){
			return array_map(function($k)use($prefix){
				return $prefix.$k;
			},$key);
		}
		return $prefix.$key;
	}
	protected function esc($val){
		if(is_numeric($val)||is_int($val))
			return (string)$val;
		else
			return serialize($val);
	}
	protected function unesc($val){
		if($val===false){
			return null;
		}
		if(is_numeric($val))
			return floatval($val);
		else if(is_string($val))
			return unserialize($val);
		else if(is_array($val)){
			foreach($val as $k=>$v){
				$val[$k]=$this->unesc($v);
			}
		}
		return $val;
	}
}
