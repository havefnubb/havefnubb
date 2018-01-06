<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage db_driver
 * @author     Yann Lecommandoux
 * @contributor Laurent Jouanneau, Louis S.
 * @copyright  2008 Yann Lecommandoux, 2011-2017 Laurent Jouanneau, Louis S.
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
require_once(__DIR__.'/sqlsrv.dbresultset.php');
class sqlsrvDbConnection extends jDbConnection{
	function __construct($profile){
		if(!function_exists('sqlsrv_connect')){
			throw new jException('jelix~db.error.nofunction','sqlsrv');
		}
		parent::__construct($profile);
	}
	public function beginTransaction(){
		sqlsrv_begin_transaction($this->_connection);
	}
	public function commit(){
		sqlsrv_commit($this->_connection);
	}
	public function rollback(){
		sqlsrv_rollback($this->_connection);
	}
	protected function _autoCommitNotify($state){
		if($state==1){
			$this->query('SET IMPLICIT_TRANSACTIONS ON');
		}else{
			$this->query('SET IMPLICIT_TRANSACTIONS OFF');
		}
	}
	public function errorInfo(){
		return sqlsrv_errors(SQLSRV_ERR_ERRORS);
	}
	public function errorCode(){
		$err=sqlsrv_errors(SQLSRV_ERR_ERRORS);
		if($err){
			return $err['code'];
		}
		return 0;
	}
	protected function _connect(){
		$connectOptions=array();
		if(isset($this->profile['user'])&&$this->profile['user']!=''){
			$connectOptions['UID']=$this->profile['user'];
		}
		if(isset($this->profile['password'])&&$this->profile['password']!=''){
			$connectOptions['PWD']=$this->profile['password'];
		}
		if(isset($this->profile['database'])&&$this->profile['database']!=''){
			$connectOptions['Database']=$this->profile['database'];
		}
		if(isset($this->profile['force_encoding'])&&$this->profile['force_encoding']==true){
			$connectOptions['CharacterSet']='UTF-8';
		}
		if($cnx=sqlsrv_connect($this->profile['host'],$connectOptions)){
			return $cnx;
		}else{
			throw new jException('jelix~db.error.connection',$this->profile['host']);
		}
	}
	protected function _disconnect(){
		return sqlsrv_close($this->_connection);
	}
	protected function _doQuery($query){
		if($stmt=sqlsrv_query($this->_connection,$query,null,array("Scrollable"=>SQLSRV_CURSOR_STATIC))){
			return new sqlsrvDbResultSet($stmt);
		}
		else{
			throw new jException('jelix~db.error.query.bad',mssql_get_last_message());
		}
	}
	protected function _doExec($query){
		if($stmt=sqlsrv_query($this->_connection,$query)){
			$nbRows=sqlsrv_rows_affected($stmt);
			sqlsrv_free_stmt($stmt);
			return $nbRows;
		}
		else{
			throw new jException('jelix~db.error.query.bad',mssql_get_last_message());
		}
	}
	protected function _doLimitQuery($queryString,$offset,$number){
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
			$orderby=' ORDER BY '.$key.' ASC';
			$from.=$orderby;
		}
		if(!$distinct)
			$queryString='SELECT TOP ';
		else
			$queryString='SELECT DISTINCT TOP ';
		$queryString.=($number+$offset). ' '.$firstField.','.implode(',',$fields).' FROM '.$from;
		$queryString='SELECT TOP ' . $number . ' * FROM (' . $queryString . ') AS inner_tbl ';
		$order_inner=preg_replace(array('/\bASC\b/i','/\bDESC\b/i'),array('_DESC','_ASC'),$orderby);
		$order_inner=str_replace(array('_DESC','_ASC'),array('DESC','ASC'),$order_inner);
		$queryString.=$order_inner;
		$queryString='SELECT TOP ' . $number . ' * FROM (' . $queryString . ') AS outer_tbl '.$orderby;
		$this->lastQuery=$queryString;
		$result=$this->_doQuery($queryString);
		return $result;
	}
	public function lastInsertId($fromSequence=''){
		$queryString='SELECT @@IDENTITY AS id';
		$result=$this->_doQuery($queryString);
		if($result){
			return $result->id;
		}
		return null;
	}
	public function prepare($query){
		list($newQuery,$parameterNames)=$this->findParameters($query,'?');
		return new sqlsrvDbResultSet(null,$this,$newQuery,$parameterNames);
	}
	public function encloseName($fieldName){
		return '['.$fieldName.']';
	}
	protected function _quote($text,$binary){
		return str_replace("'","''",$text);
	}
	public function getAttribute($id){
		return "";
	}
	public function setAttribute($id,$value){
	}
}
