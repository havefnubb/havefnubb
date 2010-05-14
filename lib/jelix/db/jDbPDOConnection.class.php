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
class jDbPDOResultSet extends PDOStatement{
	const FETCH_CLASS = 8;
	protected $_fetchMode = 0;
	public function fetch($fetch_style = PDO::FETCH_BOTH, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0){
		$rec = parent::fetch();
		if($rec && count($this->modifier)){
			foreach($this->modifier as $m)
				call_user_func_array($m, array($rec, $this));
		}
		return $rec;
	}
	public function fetchAll( $fetch_style = PDO::FETCH_OBJ, $column_index=0, $ctor_arg=null){
		if($this->_fetchMode){
			if( $this->_fetchMode != PDO::FETCH_COLUMN)
				return parent::fetchAll($this->_fetchMode);
			else
				return parent::fetchAll($this->_fetchMode, $column_index);
		}else{
			return parent::fetchAll(PDO::FETCH_OBJ);
		}
	}
	public function setFetchMode($mode, $arg1=null , $arg2=null){
		$this->_fetchMode = $mode;
		if($arg1 === null)
			return parent::setFetchMode($mode);
		else if($arg2 === null)
			return parent::setFetchMode($mode, $arg1);
		return parent::setFetchMode($mode, $arg1, $arg2);
	}
	public function unescapeBin($text){
		return $text;
	}
	protected $modifier = array();
	public function addModifier($function){
		$this->modifier[] = $function;
	}
}
class jDbPDOConnection extends PDO{
	private $_mysqlCharsets =array( 'UTF-8'=>'utf8', 'ISO-8859-1'=>'latin1');
	private $_pgsqlCharsets =array( 'UTF-8'=>'UNICODE', 'ISO-8859-1'=>'LATIN1');
	public $profile;
	public $dbms;
	function __construct($profile){
		$this->profile = $profile;
		$this->dbms = substr($profile['dsn'],0,strpos($profile['dsn'],':'));
		$prof=$profile;
		$user= '';
		$password='';
		unset($prof['dsn']);
		if(isset($prof['user'])){
			$user =$prof['user'];
			unset($prof['user']);
		}
		if(isset($prof['password'])){
			$password = $profile['password'];
			unset($prof['password']);
		}
		unset($prof['driver']);
		parent::__construct($profile['dsn'], $user, $password, $prof);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('jDbPDOResultSet'));
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		if($this->dbms == 'mysql')
			$this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		if($this->dbms == 'oci')
			$this->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
		if(isset($prof['force_encoding']) && $prof['force_encoding']==true){
			if($this->dbms == 'mysql' && isset($this->_mysqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET NAMES '".$this->_mysqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}elseif($this->dbms == 'pgsql' && isset($this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset])){
				$this->exec("SET client_encoding to '".$this->_pgsqlCharsets[$GLOBALS['gJConfig']->charset]."'");
			}
		}
	}
	public function query(){
		$args=func_get_args();
		switch(count($args)){
		case 1:
			$rs = parent::query($args[0]);
			$rs->setFetchMode(PDO::FETCH_OBJ);
			return $rs;
			break;
		case 2:
			return parent::query($args[0], $args[1]);
			break;
		case 3:
			return parent::query($args[0], $args[1], $args[2]);
			break;
		default:
			trigger_error('bad argument number in query',E_USER_ERROR);
		}
	}
	public function limitQuery($queryString, $limitOffset = null, $limitCount = null){
		if($limitOffset !== null && $limitCount !== null){
		   if($this->dbms == 'mysql' || $this->dbms == 'sqlite'){
			   $queryString.= ' LIMIT '.intval($limitOffset).','. intval($limitCount);
		   }elseif($this->dbms == 'pgsql'){
			   $queryString.= ' LIMIT '.intval($limitCount).' OFFSET '.intval($limitOffset);
		   }
		}
		$result = $this->query($queryString);
		return $result;
	}
	public function setAutoCommit($state=true){
		$this->setAttribute(PDO::ATTR_AUTOCOMMIT,$state);
	}
	public function lastIdInTable($fieldName, $tableName){
	  $rs = $this->query('SELECT MAX('.$fieldName.') as ID FROM '.$tableName);
	  if(($rs !== null) && $r = $rs->fetch()){
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
		return(isset($this->profile['table_prefix']) && $this->profile['table_prefix']!='');
	}
	public function encloseName($fieldName){
		switch($this->dbms){
			case 'mysql': return '`'.$fieldName.'`';
			case 'pgsql': return '"'.$fieldName.'"';
			default: return $fieldName;
		}
	}
}