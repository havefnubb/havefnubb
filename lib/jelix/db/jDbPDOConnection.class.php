<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor Gwendal Jouannic, Thomas, Julien Issler, Vincent Herr
* @copyright  2005-2012 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic, 2009 Thomas
* @copyright  2009 Julien Issler
* @copyright  2011 Vincent Herr
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
			if($this->dbms=='sqlite')
				$dsn=str_replace(array('app:','lib:','var:'),array(jApp::appPath(),LIB_PATH,jApp::varPath()),$dsn);
		}
		else{
			$this->dbms=$profile['driver'];
			$db=$profile['database'];
			$dsn=$this->dbms.':host='.$profile['host'].';dbname='.$db;
			if($this->dbms!='sqlite')
				$dsn=$this->dbms.':host='.$profile['host'].';dbname='.$db;
			else{
				if(preg_match('/^(app|lib|var)\:/',$db,$m))
					$dsn='sqlite:'.str_replace(array('app:','lib:','var:'),array(jApp::appPath(),LIB_PATH,jApp::varPath()),$db);
				else
					$dsn='sqlite:'.jApp::varPath('db/sqlite/'.$db);
			}
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
		parent::__construct($dsn,$user,$password,$prof);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS,array('jDbPDOResultSet'));
		$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		if($this->dbms=='mysql')
			$this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
		if($this->dbms=='oci')
			$this->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		if(isset($prof['force_encoding'])&&$prof['force_encoding']==true){
			$charset=jApp::config()->charset;
			if($this->dbms=='mysql'&&isset($this->_mysqlCharsets[$charset])){
				$this->exec("SET NAMES '".$this->_mysqlCharsets[$charset]."'");
			}
			elseif($this->dbms=='pgsql'&&isset($this->_pgsqlCharsets[$charset])){
				$this->exec("SET client_encoding to '".$this->_pgsqlCharsets[$charset]."'");
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
			elseif($this->dbms=='oci'){
				$limitOffset=$limitOffset + 1;
				$queryString='SELECT * FROM ( SELECT ocilimit.*, rownum rnum FROM ('.$queryString.') ocilimit WHERE
                    rownum<'.(intval($limitOffset)+intval($limitCount)).'  ) WHERE rnum >='.intval($limitOffset);
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
			$this->_tools=jApp::loadPlugin($this->dbms,'db','.dbtools.php',$this->dbms.'DbTools',$this);
			if(is_null($this->_tools))
				throw new jException('jelix~db.error.driver.notfound',$this->dbms);
		}
		return $this->_tools;
	}
	public function lastInsertId($fromSequence=null){
		if($this->dbms=='mssql'){
			$res=$this->query('SELECT SCOPE_IDENTITY()');
			return (int) $res->fetchColumn();
		}
		return parent::lastInsertId($fromSequence);
	}
}
