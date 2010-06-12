<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor 
* @copyright  2010 Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class pgsqlDbTable extends jDbTable{
}
class pgsqlDbSchema extends jDbSchema{
	function createTable($name,$columns,$primaryKeys,$attributes=array()){
	}
	function getTable($name){
		return  new pgsqlDbTable($this->schema->getConn()->prefixTable($name),$this);
	}
	public function getTables(){
		$results=array();
		$sql="SELECT tablename FROM pg_tables
                  WHERE schemaname NOT IN ('pg_catalog', 'information_schema')
                  ORDER BY tablename";
		$rs=$this->schema->getConn()->query($sql);
		while($line=$rs->fetch()){
			$results[]=new pgsqlDbTable($line->tablename,$this);
		}
		return $results;
	}
}
