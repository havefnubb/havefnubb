<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db_driver
* @author     Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright  2006 Loic Mathaud, 2007 Laurent Jouanneau
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class sqliteDbTools extends jDbTools{
	protected function _getTableList(){
		$results = array();
		$rs = $this->_connector->query('SELECT name FROM sqlite_master WHERE type="table"');
		while($line = $rs->fetch()){
			$results[] = $line->name;
		}
		return $results;
	}
	protected function _getFieldList($tableName){
		$results = array();
		$query = "PRAGMA table_info(". sqlite_escape_string($tableName) .")";
		$rs = $this->_connector->query($query);
		while($line = $rs->fetch()){
			$field = new jDbFieldProperties();
			$field->name = $line->name;
			$field->primary  =($line->pk == 1);
			$field->notNull   =($line->notnull == '99' || $line->pk == 1);
			if(preg_match('/^(\w+)\s*(\((\d+)\))?.*$/',$line->type,$m)){
				$field->type = strtolower($m[1]);
				if(isset($m[3])){
					$field->length = intval($m[3]);
				}
			}
			else{
				$field->type = $line->type;
			}
			if($field->type == 'integer' && $field->primary){
				$field->autoIncrement = true;
			}
			if(!$field->primary){
				if($line->dflt_value !== null ||($line->dflt_value === null && !$field->notNull)){
					$field->hasDefault = true;
					$field->default =  $line->dflt_value;
				}
			}
			$results[$line->name] = $field;
		}
		return $results;
	}
}