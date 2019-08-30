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
	public $driverName='';
	function __construct($profile){
		$this->profile=$profile;
		$prof=$profile;
		$user='';
		$password='';
		if(isset($profile['dsn'])){
			$this->dbms=$this->driverName=substr($profile['dsn'],0,strpos($profile['dsn'],':'));
			$dsn=$profile['dsn'];
			unset($prof['dsn']);
			if($this->dbms=='sqlite'){
				$dsn='sqlite:'.$this->_parseSqlitePath(substr($dsn,7));
			}
		}
		else{
			$this->dbms=$profile['driver'];
			if($this->dbms=='mysqli'){
				$this->dbms='mysql';
			}
			else if($this->dbms=='sqlite3'){
				$this->dbms='sqlite';
			}
			$this->driverName=$this->dbms;
			$db=$profile['database'];
			if($this->dbms!='sqlite'){
				$dsn=$this->dbms.':host='.$profile['host'].';dbname='.$db;
			}
			else{
				$dsn='sqlite:'.$this->_parseSqlitePath($db);
			}
			if($this->dbms=='pgsql'&&
				isset($profile['pg_options'])&&
				$profile['pg_options']!=''
			){
				$dsn.=';options='.$profile['pg_options'];
			}
		}
		if(isset($prof['usepdo'])){
			unset($prof['usepdo']);
		}
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
		if($this->dbms=='mysql'){
			$this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
		}
		if($this->dbms=='oci'){
			$this->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
		}
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
	protected function _parseSqlitePath($path){
		if(preg_match('/^(app|lib|var|temp|www)\:/',$path,$m)){
			return jFile::parseJelixPath($path);
		}
		else if(preg_match('!^[a-z]\\:(\\\\|/)[a-z]!i',$path)||
				$path[0]=='/'
				){
			if(file_exists($path)||file_exists(dirname($path))){
				return $path;
			}
			else{
				throw new Exception('jDbPDOConnection, sqlite: unknown database path scheme');
			}
		}
		else{
			return jApp::varPath('db/sqlite/'.$path);
		}
	}
	public function getProfileName(){
		return $this->profile['_name'];
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
			elseif($this->dbms=='sqlsrv'){
		$queryString=$this->limitQuerySqlsrv($queryString,$limitOffset,$limitCount);
		}
		}
		return $this->query($queryString);
	}
	protected function limitQuerySqlsrv($queryString,$limitOffset=null,$limitCount=null){
		$queryString=preg_replace('/^SELECT TOP[ ]\d*\s*/i','SELECT ',trim($queryString));
		$distinct=false;
		list($select,$from)=preg_split('/\sFROM\s/mi',$queryString,2);
		$fields=preg_split('/\s*,\s*/',$select);
		$firstField=preg_replace('/^\s*SELECT\s+/','',array_shift($fields));
		if(stripos($firstField,'DISTINCT')!==false){
			$firstField=preg_replace('/DISTINCT/i','',$firstField);
			$distinct=true;
		}
		$orderby=stristr($from,'ORDER BY');
		if($orderby===false){
			if(stripos($firstField,' as ')!==false){
			list($field,$key)=preg_split('/ as /',$firstField);
			}
			else{
			$key=$firstField;
			}
			$orderby=' ORDER BY '.strstr(strstr($key,'.'),'[').' ASC';
			$from.=$orderby;
		}else{
		if(strpos($orderby,'.',8)){
		$orderby=' ORDER BY ' . substr($orderby,strpos($orderby,'.')+ 1);
		}
	}
		if(!$distinct)
			$queryString='SELECT TOP ';
		else
			$queryString='SELECT DISTINCT TOP ';
		$queryString.=($limitCount+$limitOffset). ' '.$firstField.','.implode(',',$fields).' FROM '.$from;
		$queryString='SELECT TOP ' . $limitCount . ' * FROM (' . $queryString . ') AS inner_tbl ';
		$order_inner=preg_replace(array('/\bASC\b/i','/\bDESC\b/i'),array('_DESC','_ASC'),$orderby);
		$order_inner=str_replace(array('_DESC','_ASC'),array('DESC','ASC'),$order_inner);
		$queryString.=$order_inner;
		$queryString='SELECT TOP ' . $limitCount . ' * FROM (' . $queryString . ') AS outer_tbl '.$orderby;
		return $queryString;
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
		if(!isset($this->profile['table_prefix'])){
			return $table_name;
		}
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
			$dbms=($this->dbms==='sqlite')? 'sqlite3' : $this->dbms;
			$this->_tools=jApp::loadPlugin($dbms,'db','.dbtools.php',$dbms.'DbTools',$this);
			if(is_null($this->_tools)){
				throw new jException('jelix~db.error.driver.notfound',$dbms);
			}
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
