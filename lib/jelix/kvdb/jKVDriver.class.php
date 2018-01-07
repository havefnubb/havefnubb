<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  kvdb
 * @author      Yannick Le Guédart
 * @contributor Laurent Jouanneau
 * @copyright   2009 Yannick Le Guédart, 2010-2014 Laurent Jouanneau
 *
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
interface jIKVPersistent{
	public function sync();
}
interface jIKVttl{
	public function setWithTtl($key,$value,$ttl);
	public function garbage();
}
interface jIKVSet{
	public function sAdd($skey,$value);
	public function sRemove($skey,$value);
	public function sCount($skey);
	public function sPop($skey);
}
abstract class jKVDriver{
	protected $_profile;
	protected $_driverName;
	protected $_profileName;
	protected $_connection=null;
	public function __construct($profile){
		$this->_profile=&$profile;
		$this->_driverName=$profile['driver'];
		$this->_profileName=$profile['_name'];
		$this->_connection=$this->_connect();
	}
	public function __destruct(){
		if(! is_null($this->_connection)){
			$this->_disconnect();
		}
	}
	abstract public function get($key);
	abstract public function set($key,$value);
	abstract public function insert($key,$value);
	abstract public function replace($key,$value);
	abstract public function delete($key);
	abstract public function flush();
	abstract public function append($key,$value);
	abstract public function prepend($key,$value);
	abstract public function increment($key,$incr=1);
	abstract public function decrement($key,$decr=1);
	abstract protected function _connect();
	abstract protected function _disconnect();
}
