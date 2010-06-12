<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage db_driver
 * @author     Yann Lecommandoux
 * @copyright  2008 Yann Lecommandoux
 * @link     http://www.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
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
}
