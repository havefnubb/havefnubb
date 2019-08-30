<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  kvdb_plugin
 * @author      Yannick Le Guédart
 * @contributor Laurent Jouanneau
 * @copyright   2009 Yannick Le Guédart, 2010 Laurent Jouanneau
 *
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 *
 * @see http://fr2.php.net/manual/en/book.memcache.php
 */
class memcacheKVDriver extends jKVDriver implements jIKVttl{
	private $_servers=array();
	protected $_compress=false;
	protected function _connect(){
		if(! isset($this->_profile['host'])){
			throw new jException(
				'jelix~kvstore.error.no.host',$this->_profileName);
		}
		if(is_string($this->_profile['host'])){
			if(isset($this->_profile['port'])
				and
				strpos($this->_profile['host'],':')===false)
			{
				$server=new stdClass();
				$server->host=$this->_profile['host'];
				$server->port=(int)$this->_profile['port'];
				$this->_servers[]=$server;
			}
			else{
				foreach(explode(',',$this->_profile['host'])as $host_port){
					$hp=explode(':',$host_port);
					$server=new stdClass();
					$server->host=$hp[0];
					$server->port=(int)$hp[1];
					$this->_servers[]=$server;
				}
			}
		}
		elseif(is_array($this->_profile['host'])){
			foreach($this->_profile['host'] as $host_port){
				$hp=split(':',$host_port);
				$server=new stdClass();
				$server->host=$hp[0];
				$server->port=(int)$hp[1];
				$this->_servers[]=$server;
			}
		}
		$cnx=new Memcache();
		$oneServerAvalaible=false;
		foreach($this->_servers as $s){
			$result=@$cnx->addServer($s->host,$s->port);
			if(! $oneServerAvalaible&&$result){
				$oneServerAvalaible=true;
			}
		}
		if(! $oneServerAvalaible){
			throw new jException(
				'jelix~kvstore.error.memcache.server.unavailabled',$this->_profileName);
		}
		if(isset($this->_profile['compress'])
				and($this->_profile['compress']==1)){
			$this->_compress=true;
		}
		return $cnx;
	}
	protected function _disconnect(){
		$this->_connection->close();
	}
	public function get($key){
		$val=$this->_connection->get($key);
		if($val===false)
			return null;
		return $val;
	}
	public function set($key,$value){
		if(is_resource($value))
			return false;
		return $this->_connection->set(
			$key,
			$value,
			(($this->_compress)? MEMCACHE_COMPRESSED : 0),
			0
		);
	}
	public function insert($key,$value){
		if(is_resource($value))
			return false;
		return $this->_connection->add(
			$key,
			$value,
			(($this->_compress)? MEMCACHE_COMPRESSED : 0),
			0
		);
	}
	public function replace($key,$value){
		if(is_resource($value))
			return false;
		return $this->_connection->replace(
			$key,
			$value,
			(($this->_compress)? MEMCACHE_COMPRESSED : 0),
			0
		);
	}
	public function delete($key){
		return $this->_connection->delete($key);
	}
	public function flush(){
		return $this->_connection->flush();
	}
	public function append($key,$value){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if($this->replace($key,$oldData.$value))
			return $oldData.$value;
		else
			return false;
	}
	public function prepend($key,$value){
		$oldData=$this->get($key);
		if($oldData===null)
			return false;
		if($this->replace($key,$value.$oldData))
			return $value.$oldData;
		else
			return false;
	}
	public function increment($key,$incvalue=1){
		if(!is_numeric($incvalue)){
			return false;
		}
		$val=$this->get($key);
		if(!is_numeric($val)){
			return false;
		}else if(is_float($val)){
			$val=((int)$val)+ $incvalue;
			if($this->_connection->set($key,$val))
				return $val;
			return false;
		}
		return $this->_connection->increment($key,$incvalue);
	}
	public function decrement($key,$decvalue=1){
		if(!is_numeric($decvalue)){
			return false;
		}
		$val=$this->get($key);
		if(!is_numeric($val)){
			return false;
		}else if(is_float($val)){
			$val=((int)$val)- $decvalue;
			if($this->_connection->set($key,$val))
				return $val;
			return false;
		}
		return $this->_connection->decrement($key,$decvalue);
	}
	public function setWithTtl($key,$value,$ttl){
		if(is_resource($value))
			return false;
		return $this->_connection->set(
			$key,
			$value,
			(($this->_compress)? MEMCACHE_COMPRESSED : 0),
			$ttl
		);
	}
	public function garbage(){
		return true;
	}
}
