<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  cache
* @author      Tahina Ramaroson
* @contributor Sylvain de Vathaire, Brice Tence, Laurent Jouanneau
* @copyright   2009 Neov, 2010 Brice Tence, 2011 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
interface jICacheDriver{
	function __construct($params);
	public function get($key);
	public function set($key,$value,$ttl=0);
	public function delete($key);
	public function increment($key,$incvalue=1);
	public function decrement($key,$decvalue=1);
	public function replace($key,$value,$ttl=0);
	public function garbage();
	public function flush();
}
class jCache{
	public static function get($key,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		if(is_array($key)){
			foreach($key as $value){
				self::_checkKey($value);
			}
		}
		else{
			self::_checkKey($key);
		}
		return $drv->get($key);
	}
	public static function set($key,$value,$ttl=null,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled||is_resource($value)){
			return false;
		}
		self::_checkKey($key);
		if(is_null($ttl)){
			$ttl=$drv->ttl;
		}
		elseif(is_string($ttl)){
			if(($ttl=strtotime($ttl))===FALSE){
				throw new jException('jelix~cache.error.wrong.date.value');
			}
		}
		if($ttl > 2592000&&$ttl < time()){
			return $drv->delete($key);
		}
		if($drv->automatic_cleaning_factor > 0&&rand(1,$drv->automatic_cleaning_factor)==1){
			$drv->garbage();
		}
		return $drv->set($key,$value,$ttl);
	}
	public static function call($fn,$fnargs=array(),$ttl=null,$profile=''){
		$drv=self::_getDriver($profile);
		if($drv->enabled){
			$key=md5(serialize($fn).serialize($fnargs));
			$lockKey=$key.'___jcacheLock';
			$data=$drv->get($key);
			if($data===false){
				$lockTests=0;
				while($drv->get($lockKey)){
					usleep(100000);
					if(($lockTests++)%10==0){
						if($drv->automatic_cleaning_factor > 0&&rand(1,$drv->automatic_cleaning_factor)==1){
							$drv->garbage();
						}
					}
				}
				if($lockTests > 0){
					$data=$drv->get($key);
				}
			}
			if($data===false){
				$lockTtl=get_cfg_var('max_execution_time');
				if(!$lockTtl){
					$lockTtl=$drv->ttl;
				}
				$lockTtl=max(30,min($lockTtl,$drv->ttl));
				$drv->set($lockKey,true,$lockTtl);
				$data=self::_doFunctionCall($fn,$fnargs);
				if(!is_resource($data)){
					if(is_null($ttl)){
						$ttl=$drv->ttl;
					}elseif(is_string($ttl)){
						if(($ttl=strtotime($ttl))===FALSE){
							throw new jException('jelix~cache.error.wrong.date.value');
						}
					}
					if(!($ttl > 2592000&&$ttl < time())){
						if($drv->automatic_cleaning_factor > 0&&rand(1,$drv->automatic_cleaning_factor)==1){
							$drv->garbage();
						}
						$drv->set($key,$data,$ttl);
					}
				}
				$drv->delete($lockKey);
			}
			return $data;
		}else{
			return self::_doFunctionCall($fn,$fnargs);
		}
	}
	public static function delete($key,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		self::_checkKey($key);
		return $drv->delete($key);
	}
	public static function increment($key,$incvalue=1,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		self::_checkKey($key);
		return $drv->increment($key,$incvalue);
	}
	public static function decrement($key,$decvalue=1,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		self::_checkKey($key);
		return $drv->decrement($key,$decvalue);
	}
	public static function replace($key,$value,$ttl=null,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled||is_resource($value)){
			return false;
		}
		self::_checkKey($key);
		if(is_null($ttl)){
			$ttl=$drv->ttl;
		}
		elseif(is_string($ttl)){
			if(($ttl=strtotime($ttl))===FALSE){
				throw new jException('jelix~cache.error.wrong.date.value');
			}
		}
		if($ttl > 2592000&&$ttl < time()){
			return $drv->delete($key);
		}
		return $drv->replace($key,$value,$ttl);
	}
	public static function add($key,$value,$ttl=null,$profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled||is_resource($value)){
			return false;
		}
		self::_checkKey($key);
		if($drv->get($key)){
			return false;
		}
		if(is_null($ttl)){
			$ttl=$drv->ttl;
		}
		elseif(is_string($ttl)){
			if(($ttl=strtotime($ttl))===FALSE){
				throw new jException('jelix~cache.error.wrong.date.value');
			}
		}
		if($ttl > 2592000&&$ttl < time()){
			return false;
		}
		if($drv->automatic_cleaning_factor > 0&&rand(1,$drv->automatic_cleaning_factor)==1){
			$drv->garbage();
		}
		return $drv->set($key,$value,$ttl);
	}
	public static function garbage($profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		return $drv->garbage();
	}
	public static function flush($profile=''){
		$drv=self::_getDriver($profile);
		if(!$drv->enabled){
			return false;
		}
		return $drv->flush();
	}
	protected static function _getDriver($profile){
		global $gJConfig;
		static $drivers=array();
		$profile=($profile==''?'default':$profile);
		if(isset($drivers[$profile])){
			return $drivers[$profile];
		}
		$params=self::_getProfile($profile);
		$oDriver=$params['driver'].'CacheDriver';
		if(!class_exists($oDriver,false)){
			if(!isset($gJConfig->_pluginsPathList_cache)
				||!isset($gJConfig->_pluginsPathList_cache[$params['driver']])
				||!file_exists($gJConfig->_pluginsPathList_cache[$params['driver']])){
				throw new jException('jelix~cache.error.driver.missing',array($profile,$params['driver']));
			}
			require_once($gJConfig->_pluginsPathList_cache[$params['driver']].$params['driver'].'.cache.php');
		}
		$params['profile']=$profile;
		$drv=new $oDriver($params);
		if(!$drv instanceof jICacheDriver){
			throw new jException('jelix~cache.driver.object.invalid',array($profile,$params['driver']));
		}
		$drivers[$profile]=$drv;
		return $drv;
	}
	protected static function _checkKey($key){
		if(!preg_match('/^[a-z0-9_]+$/i',$key)||strlen($key)> 255){
			throw new jException('jelix~cache.error.invalid.key',$key);
		}
	}
	protected static function _getProfile($name){
		global $gJConfig;
		static $profiles=null;
		if($profiles===null){
			$profiles=parse_ini_file(JELIX_APP_CONFIG_PATH.$gJConfig->cacheProfiles,true);
		}
		$profile=null;
		if($name=='default'){
			if(isset($profiles['default'])&&isset($profiles[$profiles['default']])){
				$profile=$profiles[$profiles['default']];
			}
			else{
				throw new jException('jelix~cache.error.profile.missing','default');
			}
		}
		else{
			if(isset($profiles[$name])){
				$profile=$profiles[$name];
			}
			else{
				throw new jException('jelix~cache.error.profile.missing',$name);
			}
		}
		return $profile;
	}
	protected static function _doFunctionCall($fn,$fnargs){
		if(!is_callable($fn)){
			throw new jException('jelix~cache.error.function.not.callable',self::_functionToString($fn));
		}
		try{
			$data=call_user_func_array($fn,$fnargs);
		}
		catch(Exception $e){
			throw new jException('jelix~cache.error.call.function',array(self::_functionToString($fn),$e->getMessage()));
		}
		return $data;
	}
	protected static function _functionToString($fn){
		if(is_array($fn)){
			if(is_object($fn[0])){
				$fnname=get_class($fn[0])."-".$fn[1];
			}
			else{
				$fnname=implode("-",$fn);
			}
		}
		else{
			$fnname=$fn;
		}
		return $fnname;
	}
}
