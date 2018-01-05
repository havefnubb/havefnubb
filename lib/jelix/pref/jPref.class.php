<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package   jelix
* @subpackage pref
* @author    Florian Lonqueu-Brochard
* @copyright 2012 Florian Lonqueu-Brochard
* @link      http://jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jPref{
	protected function __construct(){}
	protected static $_connection;
	protected static $_prefs;
	protected static $_prefix='jpref_';
	protected static function _getConnection(){
		if(!self::$_connection){
			self::$_connection=jKVDb::getConnection('jpref');
		}
		return self::$_connection;
	}
	public static function get($key){
		if(isset(self::$_prefs[$key]))
			return self::$_prefs[$key];
		$cnx=self::_getConnection();
		$result=$cnx->get(self::$_prefix.$key);
		if(!$result){
			self::$_prefs[$key]=null;
			return null;
		}
		$type=$result[0];
		if(strlen($result)> 2)
			$value=substr($result,2);
		else
			$value="";
		if($type=='i')
			$value=(int) $value;
		elseif($type=='b')
			$value=(boolean) $value;
		elseif($type=='d')
			$value=(float) $value;
		self::$_prefs[$key]=$value;
		return $value;
	}
	public static function set($key,$value){
		self::$_prefs[$key]=$value;
		$cnx=self::_getConnection();
		if(is_int($value))
			$prefix='i';
		elseif(is_bool($value)){
			$prefix='b';
			if(!$value)
				$value='0';
		}
		elseif(is_float($value))
			$prefix='d';
		else
			$prefix='s';
		$prefix.='|';
		$cnx->set(self::$_prefix.$key,$prefix.$value);
	}
	public static function clearCache(){
		self::$_prefs=null;
	}
}
