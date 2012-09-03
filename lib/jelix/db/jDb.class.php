<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  db
* @author      Laurent Jouanneau
* @contributor Yannick Le Guédart, Laurent Raufaste, Christophe Thiriot, Julien Issler
* @copyright   2005-2011 Laurent Jouanneau, 2008 Laurent Raufaste
* @copyright   2011 Julien Issler
*
* Some of this classes were get originally from the Copix project
* (CopixDbConnection, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this classes were adapted for Jelix by Laurent Jouanneau
*
* @link     http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
abstract class jDbConnection{
	const FETCH_OBJ=5;
	const FETCH_CLASS=8;
	const FETCH_INTO=9;
	const ATTR_AUTOCOMMIT=0;
	const ATTR_PREFETCH=1;
	const ATTR_TIMEOUT=2;
	const ATTR_ERRMODE=3;
	const ATTR_SERVER_VERSION=4;
	const ATTR_SERVER_INFO=6;
	const ATTR_CLIENT_VERSION=5;
	const ATTR_CONNECTION_STATUS=7;
	const ATTR_CASE=8;
	const ATTR_CURSOR=10;
	const ATTR_ORACLE_NULLS=11;
	const ATTR_PERSISTENT=12;
	const ATTR_DRIVER_NAME=16;
	const CURSOR_FWDONLY=0;
	const CURSOR_SCROLL=1;
	public $profile;
	public $dbms;
	public $msgError='';
	public $lastQuery;
	private $_autocommit=true;
	protected $_connection=null;
	function __construct($profile){
		$this->profile=& $profile;
		$this->dbms=$profile['driver'];
		$this->_connection=$this->_connect();
	}
	function __destruct(){
		if($this->_connection!==null){
			$this->_disconnect();
		}
	}
	public function query($queryString,$fetchmode=self::FETCH_OBJ,$arg1=null,$ctoargs=null){
		$this->lastQuery=$queryString;
		$result=$this->_doQuery($queryString);
		if($fetchmode!=self::FETCH_OBJ){
			$result->setFetchMode($fetchmode,$arg1,$ctoargs);
		}
		return $result;
	}
	public function limitQuery($queryString,$limitOffset,$limitCount){
		$this->lastQuery=$queryString;
		return $this->_doLimitQuery($queryString,intval($limitOffset),intval($limitCount));
	}
	public function exec($query){
		$this->lastQuery=$query;
		return $this->_doExec($query);
	}
	public function quote($text,$parameter_type=0){
		if($parameter_type===false||$parameter_type===true)
			trigger_error("signature of jDbConnection::quote has changed, you should use quote2()",E_USER_WARNING);
		return "'".$this->_quote($text,false)."'";
	}
	public function quote2($text,$checknull=true,$binary=false){
		if($checknull)
			return(is_null($text)? 'NULL' : "'".$this->_quote($text,$binary)."'");
		else
			return "'".$this->_quote($text,$binary)."'";
	}
	public function encloseName($fieldName){
		return $fieldName;
	}
	public function encloseFieldName($fieldName){
		return $this->encloseName($fieldName);
	}
	public function prefixTable($table_name){
		if(!isset($this->profile['table_prefix']))
			return $table_name;
		return $this->profile['table_prefix'].$table_name;
	}
	public function hasTablePrefix(){
		return(isset($this->profile['table_prefix'])&&$this->profile['table_prefix']!='');
	}
	public function setAutoCommit($state=true){
		$this->_autocommit=$state;
		$this->_autoCommitNotify($this->_autocommit);
	}
	abstract public function beginTransaction();
	abstract public function commit();
	abstract public function rollback();
	abstract public function prepare($query);
	abstract public function errorInfo();
	abstract public function errorCode();
	abstract public function lastInsertId($fromSequence='');
	abstract public function getAttribute($id);
	abstract public function setAttribute($id,$value);
	public function lastIdInTable($fieldName,$tableName){
		$rs=$this->query('SELECT MAX('.$fieldName.') as ID FROM '.$tableName);
		if(($rs!==null)&&$r=$rs->fetch()){
			return $r->ID;
		}
		return 0;
	}
	abstract protected function _autoCommitNotify($state);
	abstract protected function _connect();
	abstract protected function _disconnect();
	abstract protected function _doQuery($queryString);
	abstract protected function _doExec($queryString);
	abstract protected function _doLimitQuery($queryString,$offset,$number);
	protected function _quote($text,$binary){
		return addslashes($text);
	}
	protected $_tools=null;
	public function tools(){
		if(!$this->_tools){
			require_once(jApp::config()->_pluginsPathList_db[$this->dbms].$this->dbms.'.dbtools.php');
			$class=$this->dbms.'DbTools';
			$this->_tools=new $class($this);
		}
		return $this->_tools;
	}
	protected $_schema=null;
	public function schema(){
		if(!$this->_schema){
			require_once(jApp::config()->_pluginsPathList_db[$this->dbms].$this->dbms.'.dbschema.php');
			$class=$this->dbms.'DbSchema';
			$this->_schema=new $class($this);
		}
		return $this->_schema;
	}
}
abstract class jDbResultSet implements Iterator{
	protected $_idResult=null;
	protected $_fetchMode=0;
	protected $_fetchModeParam=null;
	protected $_fetchModeCtoArgs=null;
	function __construct($idResult){
		$this->_idResult=$idResult;
	}
	function __destruct(){
		if($this->_idResult){
			$this->_free();
			$this->_idResult=null;
		}
	}
	public function id(){return $this->_idResult;}
	public function unescapeBin($text){
		return $text;
	}
	protected $modifier=array();
	public function addModifier($function){
		$this->modifier[]=$function;
	}
	public function setFetchMode($fetchmode,$param=null,$ctoargs=null){
		$this->_fetchMode=$fetchmode;
		$this->_fetchModeParam=$param;
		$this->_fetchModeCtoArgs=$ctoargs;
	}
	public function fetch(){
		$result=$this->_fetch();
		if(!$result)
			return $result;
		if(count($this->modifier)){
			foreach($this->modifier as $m)
				call_user_func_array($m,array($result,$this));
		}
		if($this->_fetchMode==jDbConnection::FETCH_OBJ)
			return $result;
		if($this->_fetchMode==jDbConnection::FETCH_CLASS){
			if($result instanceof $this->_fetchModeParam)
				return $result;
			$values=get_object_vars($result);
			$o=$this->_fetchModeParam;
			$result=new $o();
			foreach($values as $k=>$value){
				$result->$k=$value;
			}
		}
		else if($this->_fetchMode==jDbConnection::FETCH_INTO){
			$values=get_object_vars($result);
			$result=$this->_fetchModeParam;
			foreach($values as $k=>$value){
				$result->$k=$value;
			}
		}
		return $result;
	}
	public function fetchAll(){
		$result=array();
		while($res=$this->fetch()){
			$result[]=$res;
		}
		return $result;
	}
	public function getAttribute($attr){return null;}
	public function setAttribute($attr,$value){}
	abstract public function bindColumn($column,&$param,$type=null);
	abstract public function bindParam($parameter,&$variable,$data_type=null,$length=null,$driver_options=null);
	abstract public function bindValue($parameter,$value,$data_type);
	abstract public function columnCount();
	abstract public function execute($parameters=null);
	abstract public function rowCount();
	abstract protected function _free();
	abstract protected function _fetch();
	abstract protected function _rewind();
	protected $_currentRecord=false;
	protected $_recordIndex=0;
	public function current(){
		return $this->_currentRecord;
	}
	public function key(){
		return $this->_recordIndex;
	}
	public function next(){
		$this->_currentRecord=$this->fetch();
		if($this->_currentRecord)
			$this->_recordIndex++;
	}
	public function rewind(){
		$this->_rewind();
		$this->_recordIndex=0;
		$this->_currentRecord=$this->fetch();
	}
	public function valid(){
		return($this->_currentRecord!=false);
	}
}
class jDb{
	public static function getConnection($name=''){
		return jProfiles::getOrStoreInPool('jdb',$name,array('jDb','_createConnector'));
	}
	public static function getDbWidget($name=null){
		$dbw=new jDbWidget(self::getConnection($name));
		return $dbw;
	}
	public static function getTools($name=null){
		$cnx=self::getConnection($name);
		return $cnx->tools();
	}
	public static function getProfile($name='',$noDefault=false){
		return jProfiles::get('jdb',$name,$noDefault);
	}
	public function testProfile($profile){
		try{
			self::_createConnector($profile);
			$ok=true;
		}
		catch(Exception $e){
			$ok=false;
		}
		return $ok;
	}
	public static function _createConnector($profile){
		if($profile['driver']=='pdo'||(isset($profile['usepdo'])&&$profile['usepdo'])){
			$dbh=new jDbPDOConnection($profile);
			return $dbh;
		}
		else{
			$dbh=jApp::loadPlugin($profile['driver'],'db','.dbconnection.php',$profile['driver'].'DbConnection',$profile);
			if(is_null($dbh))
				throw new jException('jelix~db.error.driver.notfound',$profile['driver']);
			return $dbh;
		}
	}
	public static function createVirtualProfile($name,$params){
		jProfiles::createVirtualProfile('jdb',$name,$params);
	}
	public static function clearProfiles(){
		jProfiles::clear();
	}
	public static function floatToStr($value){
		if(is_float($value))
			return rtrim(sprintf('%.20F',$value),'0');
		else if(is_integer($value))
			return sprintf('%d',$value);
		else if(is_numeric($value))
			return $value;
		return (string)(floatval($value));
	}
}
