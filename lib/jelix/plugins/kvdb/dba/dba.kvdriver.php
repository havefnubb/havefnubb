<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage kvdb
* @author     Laurent Jouanneau
* @copyright  2012 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class dbaKVDriver extends jKVDriver implements jIKVPersistent{
	public function get($key){
		if(is_array($key)){
			$result=array();
			foreach($key as $k){
				if(dba_exists($k,$this->_connection)){
					$result[$k]=unserialize(dba_fetch($k,$this->_connection));
				}
			}
			return $result;
		}
		else{
			if(dba_exists($key,$this->_connection)){
				return unserialize(dba_fetch($key,$this->_connection));
			}
		}
		return null;
	}
	public function set($key,$value){
		if(is_resource($value))
			return false;
		if(dba_exists($key,$this->_connection))
			return dba_replace($key,serialize($value),$this->_connection);
		else
			return dba_insert($key,serialize($value),$this->_connection);
	}
	public function insert($key,$value){
		if(is_resource($value))
			return false;
		return dba_insert($key,serialize($value),$this->_connection);
	}
	public function replace($key,$value){
		if(is_resource($value))
			return false;
		if(dba_exists($key,$this->_connection))
			return dba_replace($key,serialize($value),$this->_connection);
		return false;
	}
	public function delete($key){
		return dba_delete($key,$this->_connection);
	}
	public function flush(){
		$key=dba_firstkey($this->_connection);
		$handle_later=array();
		while($key!=false){
			$handle_later[]=$key;
			$key=dba_nextkey($this->_connection);
		}
		foreach($handle_later as $val){
			dba_delete($val,$this->_connection);
		}
		return true;
	}
	public function append($key,$value){
		if(is_resource($value))
			return false;
		if(!dba_exists($key,$this->_connection))
			return false;
		$value=unserialize(dba_fetch($key,$this->_connection)). $value;
		dba_replace($key,serialize($value),$this->_connection);
		return $value;
	}
	public function prepend($key,$value){
		if(is_resource($value))
			return false;
		if(!dba_exists($key,$this->_connection))
			return false;
		$value.=unserialize(dba_fetch($key,$this->_connection));
		dba_replace($key,serialize($value),$this->_connection);
		return $value;
	}
	public function increment($key,$incr=1){
		if(!is_numeric($incr)){
			return false;
		}
		if(!dba_exists($key,$this->_connection))
			return false;
		$value=unserialize(dba_fetch($key,$this->_connection));
		if(!is_numeric($value))
			return false;
		$value=serialize($value + $incr);
		dba_replace($key,$value,$this->_connection);
		return true;
	}
	public function decrement($key,$decr=1){
		if(!is_numeric($decr)){
			return false;
		}
		if(!dba_exists($key,$this->_connection))
			return false;
		$value=unserialize(dba_fetch($key,$this->_connection));
		if(!is_numeric($value))
			return false;
		$value=serialize($value - $decr);
		dba_replace($key,$value,$this->_connection);
		return true;
	}
	protected $_file;
	protected function _connect(){
		if(isset($this->_profile['file'])&&$this->_profile['file']!=''){
			$this->_file=str_replace(array('var:','temp:'),array(jApp::varPath(),jApp::tempPath()),$this->_profile['file']);
		}
		else
			throw new Exception('No file in the configuration of the dba driver for jKVDB');
		$mode="cl";
		if(isset($this->_profile['handler'])&&$this->_profile['handler']!=''){
			$handler=$this->_profile['handler'];
		}
		else
			throw new Exception('No handler in the configuration of the dba driver for jKVDB');
		if(isset($this->_profile['persistant'])&&$this->_profile['persistant'])
			$conn=dba_popen($this->_file,$mode,$handler);
		else
			$conn=dba_open($this->_file,$mode,$handler);
		if($conn===false)
			return null;
		return $conn;
	}
	protected function _disconnect(){
		dba_close($this->_connection);
	}
	public function sync(){
		return dba_sync($this->_connection);
	}
}
