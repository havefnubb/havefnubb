<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  events
* @author      Gérald Croes, Patrice Ferlet
* @contributor Laurent Jouanneau, Dominique Papin, Steven Jehannet
* @copyright 2001-2005 CopixTeam, 2005-2012 Laurent Jouanneau, 2009 Dominique Papin
* This classes were get originally from the Copix project
* (CopixEvent*, CopixListener* from Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and  Patrice Ferlet,
* and this classes were adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jEventListener{
	function performEvent($event){
		$methodName='on'.$event->getName();
		$this->$methodName($event);
	}
}
class jEvent{
	protected $_name=null;
	protected $_params=null;
	protected $_responses=array();
	function __construct($name,$params=array()){
		$this->_name=$name;
		$this->_params=& $params;
	}
	function __get($name){
		return $this->getParam($name);
	}
	function __set($name,$value){
		return $this->_params[$name]=$value;
	}
	public function getName(){
		return $this->_name;
	}
	public function getParam($name){
		if(isset($this->_params[$name])){
			$ret=$this->_params[$name];
		}else{
			$ret=null;
		}
		return $ret;
	}
	public function add($response){
		$this->_responses[]=& $response;
	}
	public function inResponse($responseName,$value,& $response){
		$founded=false;
		$response=array();
		foreach($this->_responses as $key=>$listenerResponse){
			if(isset($listenerResponse[$responseName])&&$listenerResponse[$responseName]==$value){
				$founded=true;
				$response[]=& $this->_responses[$key];
			}
		}
		return $founded;
	}
	public function getResponse(){
		return $this->_responses;
	}
	public static function notify($eventname,$params=array()){
		$event=new jEvent($eventname,$params);
		if(!isset(self::$hashListened[$eventname])){
			self::loadListenersFor($eventname);
		}
		$list=& self::$hashListened[$eventname];
		foreach(array_keys($list)as $key){
			$list[$key]->performEvent($event);
		}
		return $event;
	}
	protected static $compilerData=array('jEventCompiler',
					'events/jEventCompiler.class.php',
					'events.xml',
					'events.php'
					);
	protected static $listenersSingleton=array();
	protected static $hashListened=array();
	protected static function loadListenersFor($eventName){
		if(!isset($GLOBALS['JELIX_EVENTS'])){
			$compilerData=self::$compilerData;
			$compilerData[3]=jApp::config()->urlengine['urlScriptId'].'.'.$compilerData[3];
			jIncluder::incAll($compilerData,true);
		}
		$inf=& $GLOBALS['JELIX_EVENTS'];
		self::$hashListened[$eventName]=array();
		if(isset($inf[$eventName])){
			$modules=& jApp::config()->_modulesPathList;
			foreach($inf[$eventName] as $listener){
				list($module,$listenerName)=$listener;
				if(! isset($modules[$module]))
					continue;
				if(! isset(self::$listenersSingleton[$module][$listenerName])){
					require_once($modules[$module].'classes/'.$listenerName.'.listener.php');
					$className=$listenerName.'Listener';
					self::$listenersSingleton[$module][$listenerName]=new $className();
				}
				self::$hashListened[$eventName][]=self::$listenersSingleton[$module][$listenerName];
			}
		}
	}
	public static function clearCache(){
		self::$hashListened=array();
		self::$listenersSingleton=array();
		unset($GLOBALS['JELIX_EVENTS']);
	}
}
