<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage db_driver
 * @author     Yann Lecommandoux
 * @contributor Laurent Jouanneau, Louis S.
 * @copyright  2008 Yann Lecommandoux, 2011-2012 Laurent Jouanneau, Louis S.
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
require_once(__DIR__.'/mssql.dbresultset.php');
class mssqlDbConnection extends jDbConnection{
	function __construct($profile){
		if(!function_exists('mssql_connect')){
			throw new jException('jelix~db.error.nofunction','mssql');
		}
		parent::__construct($profile);
	}
	public function beginTransaction(){
		$this->_doExec('SET IMPLICIT_TRANSACTIONS OFF');
		$this->_doExec('BEGIN TRANSACTION');
	}
	public function commit(){
		$this->_doExec('COMMIT TRANSACTION');
		$this->_doExec('SET IMPLICIT_TRANSACTIONS ON');
	}
	public function rollback(){
		$this->_doExec('ROLLBACK TRANSACTION');
		$this->_doExec('SET IMPLICIT_TRANSACTIONS ON');
	}
	public function prepare($query){
		throw new jException('jelix~db.error.feature.unsupported',array('mssql','prepare'));
	}
	public function errorInfo(){
		return array('HY000',mssql_get_last_message());
	}
	public function errorCode(){
		return mssql_get_last_message();
	}
	protected function _connect(){
		$funcconnect=($this->profile['persistent']? 'mssql_pconnect':'mssql_connect');
		if($cnx=@$funcconnect($this->profile['host'],$this->profile['user'],$this->profile['password'])){
			return $cnx;
		}else{
			throw new jException('jelix~db.error.connection',$this->profile['host']);
		}
	}
	protected function _disconnect(){
		return mssql_close($this->_connection);
	}
	protected function _doQuery($query){
		if(!mssql_select_db($this->profile['database'],$this->_connection)){
			if(mssql_get_last_message()){
				throw new jException('jelix~db.error.database.unknown',$this->profile['database']);
			}else{
				throw new jException('jelix~db.error.connection.closed',$this->profile['name']);
			}
		}
		if($qI=mssql_query($query,$this->_connection)){
			return new mssqlDbResultSet($qI);
		}else{
			throw new jException('jelix~db.error.query.bad',mssql_get_last_message());
		}
	}
	protected function _doExec($query){
		if(!mssql_select_db($this->profile['database'],$this->_connection))
		throw new jException('jelix~db.error.database.unknown',$this->profile['database']);
		if($qI=mssql_query($query,$this->_connection)){
			return mssql_rows_affected($this->_connection);
		}else{
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
		return $result;
	}
	protected function _autoCommitNotify($state){
		if($state==1){
			$this->query('SET IMPLICIT_TRANSACTIONS ON');
		}else{
			$this->query('SET IMPLICIT_TRANSACTIONS OFF');
		}
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
