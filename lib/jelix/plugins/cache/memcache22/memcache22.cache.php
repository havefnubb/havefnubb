<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage cache_plugin
* @author     Tahina Ramaroson
* @contributor Sylvain de Vathaire
* @copyright  2009 Neov, 2010 Neov
* @link     http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class memcache22CacheDriver implements jICacheDriver{
	protected $_servers='127.0.0.1:11211';
	protected $_memcache;
	public $profil_name;
	public $enabled=true;
	public $ttl=0;
	public $automatic_cleaning_factor=0;
	public function __construct($params){
		if(!extension_loaded('memcache')){
			throw new jException('jelix~cache.error.memcache.extension.missing',array($this->profil_name,''));
		}
		if(version_compare(phpversion('memcache'),'3.0.1')>=0){
			throw new jException('jelix~cache.error.memcache.extension.badversion.2',array($this->profil_name));
		}
		$this->profil_name=$params['_name'];
		if(isset($params['enabled'])){
			$this->enabled=($params['enabled'])?true:false;
		}
		if(isset($params['ttl'])){
			$this->ttl=$params['ttl'];
		}
		$this->_memcache=new Memcache;
		if(isset($params['servers'])){
			$this->_servers=$params['servers'];
		}
		$servers=explode(',',$this->_servers);
		$fails=0;
		for($i=0;$i<count($servers);$i++){
			list($server,$port)=explode(':',$servers[$i]);
			if(!$this->_memcache->addServer($server,(int)$port)){
				$fails++;
			}
		}
		if($fails==$i){
			throw new jException('jelix~cache.error.no.memcache.server.available',$this->profil_name);
		}
	}
	public function get($key){
		return $this->_memcache->get($key);
	}
	public function set($key,$var,$ttl=0){
		return $this->_memcache->set($key,$var,0,$ttl);
	}
	public function delete($key){
		return $this->_memcache->delete($key);
	}
	public function increment($key,$var=1){
		if(!is_numeric($var)||!is_numeric($this->get($key))){
			return false;
		}
		return $this->_memcache->increment($key,$var);
	}
	public function decrement($key,$var=1){
		if(!is_numeric($var)||!is_numeric($this->get($key))){
			return false;
		}
		return $this->_memcache->decrement($key,$var);
	}
	public function replace($key,$var,$ttl=0){
		return $this->_memcache->replace($key,$var,0,$ttl);
	}
	public function garbage(){
		return true;
	}
	public function flush(){
		return $this->_memcache->flush();
	}
}
