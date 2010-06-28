<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor Loic Mathaud
* @contributor Christophe Thiriot
* @copyright   2005-2007 Laurent Jouanneau
* @copyright   2008 Christophe Thiriot
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jClasses{
	static protected $_instances=array();
	static protected $_bindings=array();
	private function __construct(){}
	static public function create($selector){
		$sel=new jSelectorClass($selector);
		require_once($sel->getPath());
		$class=$sel->className;
		return new $class();
	}
	static public function createBinded($selector){
		return self::bind($selector)->getInstance(false);
	}
	static public function createInstance($selector){
		return self::create($selector);
	}
	static public function getService($selector){
		$sel=new jSelectorClass($selector);
		$s=$sel->toString();
		if(isset(self::$_instances[$s])){
			return self::$_instances[$s];
		}else{
			$o=self::create($selector);
			self::$_instances[$s]=$o;
			return $o;
		}
	}
	static public function getBindedService($selector){
		return self::bind($selector)->getInstance();
	}
	static public function bind($selector){
		$osel=jSelectorFactory::create($selector,'iface');
		$s=$osel->toString(true);
		if(!isset(self::$_bindings[$s])){
			self::$_bindings[$s]=new jClassBinding($osel);
		}
		return self::$_bindings[$s];
	}
	static public function resetBindings(){
		self::$_bindings=array();
	}
	static public function inc($selector){
		$sel=new jSelectorClass($selector);
		require_once($sel->getPath());
	}
	static public function incIface($selector){
		$sel=new jSelectorIface($selector);
		require_once($sel->getPath());
	}
}
