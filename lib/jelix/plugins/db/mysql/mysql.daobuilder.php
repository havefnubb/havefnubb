<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db_driver
* @author     Laurent Jouanneau
* @contributor
* @copyright  2007 Laurent Jouanneau
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class mysqlDaoBuilder extends jDaoGenerator{
	protected $propertiesListForInsert = 'PrimaryFieldsExcludeAutoIncrement';
	function __construct($factoryClassName, $recordClassName, $daoDefinition){
		parent::__construct($factoryClassName, $recordClassName, $daoDefinition);
	}
	protected function _encloseName($name){
		return '`'.$name.'`';
	}
}