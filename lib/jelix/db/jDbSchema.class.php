<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor Aurélien Marcel
* @copyright  2017 Laurent Jouanneau, 2011 Aurélien Marcel
*
* @link        http://jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once(JELIX_LIB_PATH.'db/jDbTable.class.php');
require_once(JELIX_LIB_PATH.'db/jDbColumn.class.php');
abstract class jDbSchema{
	protected $conn;
	function __construct(jDbConnection $conn){
		$this->conn=$conn;
	}
	public function getConn(){
		return $this->conn;
	}
	function createTable($name,$columns,$primaryKey,$attributes=array()){
		$prefixedName=$this->conn->prefixTable($name);
		if($this->tables===null){
			$this->tables=$this->_getTables();
		}
		if(isset($this->tables[$name])){
			return null;
		}
		$this->tables[$name]=$this->_createTable($prefixedName,$columns,$primaryKey,$attributes);
		return $this->tables[$name];
	}
	function getTable($name){
		if($this->tables===null){
			$this->tables=$this->_getTables();
		}
		if(isset($this->tables[$name])){
			return $this->tables[$name];
		}
		return null;
	}
	protected $tables=null;
	public function getTables(){
		if($this->tables===null){
			$this->tables=$this->_getTables();
		}
		return $this->tables;
	}
	public function dropTable($table){
		if($this->tables===null){
			$this->tables=$this->_getTables();
		}
		if(is_string($table)){
			$name=$this->conn->prefixTable($table);
			$unprefixedName=$table;
		}
		else{
			$name=$table->getName();
			$unprefixedName=$this->conn->unprefixTable($name);
		}
		if(isset($this->tables[$unprefixedName])){
			$this->_dropTable($name);
			unset($this->tables[$unprefixedName]);
		}
	}
	public function renameTable($oldName,$newName){
		if($this->tables===null){
			$this->tables=$this->_getTables();
		}
		if(isset($this->tables[$newName])){
			return $this->tables[$newName];
		}
		if(isset($this->tables[$oldName])){
			$newPrefixedName=$this->conn->prefixTable($newName);
			$this->_renameTable(
				$this->conn->prefixTable($oldName),
				$newPrefixedName);
			unset($this->tables[$oldName]);
			$this->tables[$newName]=$this->_getTableInstance($newPrefixedName);
			return $this->tables[$newName];
		}
		return null;
	}
	abstract protected function _createTable($name,$columns,$primaryKey,$attributes=array());
	protected function _createTableQuery($name,$columns,$primaryKey,$attributes=array()){
		$cols=array();
		if(is_string($primaryKey)){
			$primaryKey=array($primaryKey);
		}
		foreach($columns as $col){
			$isPk=(in_array($col->name,$primaryKey));
			$isSinglePk=$isPk&&(count($primaryKey)==1);
			$cols[]=$this->_prepareSqlColumn($col,$isPk,$isSinglePk);
		}
		if(isset($attributes['temporary'])&&$attributes['temporary']){
			$sql='CREATE TEMPORARY TABLE ';
		}
		else{
			$sql='CREATE TABLE ';
		}
		$sql.=$this->conn->encloseName($name).' ('.implode(", ",$cols);
		if(count($primaryKey)> 1){
			$pkName=$this->conn->encloseName($name.'_pkey');
			$pkEsc=array();
			foreach($primaryKey as $k){
				$pkEsc[]=$this->conn->encloseName($k);
			}
			$sql.=', CONSTRAINT '.$pkName.' PRIMARY KEY ('.implode(',',$pkEsc).')';
		}
		$sql.=')';
		return $sql;
	}
	abstract protected function _getTables();
	protected function _dropTable($name){
		$this->conn->exec('DROP TABLE '.$this->conn->encloseName($name));
	}
	protected function _renameTable($oldName,$newName){
		$this->conn->exec('ALTER TABLE '.$this->conn->encloseName($oldName).
		' RENAME TO '.$this->conn->encloseName($newName));
	}
	abstract protected function _getTableInstance($name);
	protected $supportAutoIncrement=false;
	function _prepareSqlColumn($col,$isPrimaryKey=false,$isSinglePrimaryKey=false){
		$this->normalizeColumn($col);
		$colstr=$this->conn->encloseName($col->name).' '.$col->nativeType;
		$ti=$this->conn->tools()->getTypeInfo($col->type);
		if($col->precision){
			$colstr.='('.$col->precision;
			if($col->scale){
				$colstr.=','.$col->scale;
			}
			$colstr.=')';
		}
		else if($col->length&&$ti[1]!='text'&&$ti[1]!='blob'){
			$colstr.='('.$col->length.')';
		}
		if($this->supportAutoIncrement&&$col->autoIncrement){
			$colstr.=' AUTO_INCREMENT ';
		}
		$colstr.=($col->notNull?' NOT NULL':'');
		if(!$col->autoIncrement&&!$isPrimaryKey){
			if($col->hasDefault){
				if($col->default===null||strtoupper($col->default)=='NULL'){
					if(!$col->notNull){
						$colstr.=' DEFAULT NULL';
					}
				}else{
					$colstr.=' DEFAULT ' . $this->conn->tools()->escapeValue($ti[1],$col->default,true);
				}
			}
		}
		if($isSinglePrimaryKey){
			$colstr.=' PRIMARY KEY ';
		}
		return $colstr;
	}
	function normalizeColumn($col){
		$type=$this->conn->tools()->getTypeInfo($col->type);
		$col->nativeType=$type[0];
		if($type[6]){
			$col->autoIncrement=true;
			$col->notNull=true;
		}
	}
}
