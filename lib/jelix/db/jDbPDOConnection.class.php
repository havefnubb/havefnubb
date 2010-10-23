<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor Gwendal Jouannic, Thomas, Julien Issler
* @copyright  2005-2010 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic, 2009 Thomas
* @copyright  2009 Julien Issler
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbPDOConnection extends PDO{
	private $_mysqlCharsets=array('UTF-8'=>'utf8','ISO-8859-1'=>'latin1');
	private $_pgsqlCharsets=array('UTF-8'=>'UNICODE','ISO-8859-1'=>'LATIN1');
	public $profile;
	public $dbms;
	function __construct($profile){
		$this->profile=$profile;
		$prof=$profile;
		$user='';
		$password='';
		$dsn='';
		if(isset($profile['dsn'])){
			$this->dbms=substr($profile['dsn'],0,strpos($profile['dsn'],':'));
			$dsn=$profile['dsn'];
			unset($prof['dsn']);
		}
		else{
			$this->dbms=$profile['driver'];
			$dsn=$this->dbms.':host='.$profile['host'].';dbname='.$profile['database'];
		}
		if(isset($prof['usepdo']))
			unset($prof['usepdo']);
		if(isset($prof['user'])){
			$user=$prof['user'];
			unset($prof['user']);
		}
		if(isset($prof['password'])){
			$password=$profile['password'];
			unset($prof['password']);
		}
		unset($prof['driver']);
		if($this->dbms=='sqlite')
			$dsn=str_replace(array('app:','lib:'),array(JELIX_APP_PATH,LIB_PATH),$dsn);
		parent::__construct($dsn,$user,$password,$prof);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS,array('jDbPDOResultSet'));
		$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		if($this->dbms=='mysql')
			$this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
		if($this->dbms=='oci')
			$this->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		if(isset($prof['force_encoding'])&&$prof['force_encoding']==true){
			if($this->dbms=='mysql'&&isset($this->_mysqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET NAMES '".$this->_mysqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}
			elseif($this->dbms=='pgsql'&&isset($this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET client_encoding to '".$this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}
		}
	}
	public function query(){
		$args=func_get_args();
		switch(count($args)){
		case 1:
			$rs=parent::query($args[0]);
			$rs->setFetchMode(PDO::FETCH_OBJ);
			return $rs;
		case 2:
			return parent::query($args[0],$args[1]);
		case 3:
			return parent::query($args[0],$args[1],$args[2]);
		default:
			throw new Exception('jDbPDOConnection: bad argument number in query');
		}
	}
	public function limitQuery($queryString,$limitOffset=null,$limitCount=null){
		if($limitOffset!==null&&$limitCount!==null){
			if($this->dbms=='mysql'||$this->dbms=='sqlite'){
				$queryString.=' LIMIT '.intval($limitOffset).','. intval($limitCount);
			}
			elseif($this->dbms=='pgsql'){
				$queryString.=' LIMIT '.intval($limitCount).' OFFSET '.intval($limitOffset);
			}
		}
		return $this->query($queryString);
	}
	public function setAutoCommit($state=true){
		$this->setAttribute(PDO::ATTR_AUTOCOMMIT,$state);
	}
	public function lastIdInTable($fieldName,$tableName){
	$rs=$this->query('SELECT MAX('.$fieldName.') as ID FROM '.$tableName);
	if(($rs!==null)&&$r=$rs->fetch()){
		return $r->ID;
	}
	return 0;
	}
	public function prefixTable($table_name){
		if(!isset($this->profile['table_prefix']))
			return $table_name;
		return $this->profile['table_prefix'].$table_name;
	}
	public function hasTablePrefix(){
		return(isset($this->profile['table_prefix'])&&$this->profile['table_prefix']!='');
	}
	public function encloseName($fieldName){
		switch($this->dbms){
			case 'mysql': return '`'.$fieldName.'`';
			case 'pgsql': return '"'.$fieldName.'"';
			default: return $fieldName;
		}
	}
	public function quote2($text,$checknull=true,$binary=false){
		if($checknull)
			return(is_null($text)? 'NULL' : $this->quote($text));
		else
			return $this->quote($text);
	}
	protected $_tools=null;
	public function tools(){
		if(!$this->_tools){
			global $gJConfig;
			if(!isset($gJConfig->_pluginsPathList_db[$this->dbms])
				||!file_exists($gJConfig->_pluginsPathList_db[$this->dbms])){
				throw new jException('jelix~db.error.driver.notfound',$this->dbms);
			}
			require_once($gJConfig->_pluginsPathList_db[$this->dbms].$this->dbms.'.dbtools.php');
			$class=$this->dbms.'DbTools';
			$this->_tools=new $class($this);
		}
		return $this->_tools;
	}
}
