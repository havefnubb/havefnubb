<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Loic Mathaud
* @copyright  2008 Loic Mathaud
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jMessage{
	protected static $session_name='JELIX_MESSAGE';
	public static function add($message,$type='default'){
		$_SESSION[self::$session_name][$type][]=$message;
	}
	public static function clear($type='default'){
		$_SESSION[self::$session_name][$type]=array();
	}
	public static function clearAll(){
		$_SESSION[self::$session_name]=array();
	}
	public static function get($type='default'){
		if(isset($_SESSION[self::$session_name][$type])){
			return $_SESSION[self::$session_name][$type];
		}
		return null;
	}
	public static function getAll(){
		if(isset($_SESSION[self::$session_name])){
			return $_SESSION[self::$session_name];
		}
		return null;
	}
}
