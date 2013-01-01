<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db_driver
* @author     Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright  2006 Loic Mathaud, 2008-2012 Laurent Jouanneau
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class sqlite3DbResultSet extends jDbResultSet{
	protected function _fetch(){
		$res=$this->_idResult->fetchArray(SQLITE3_ASSOC);
		if($res===false)
			return $res;
		return (object)$res;
	}
	protected function _free(){
		$this->_idResult->finalize();
	}
	protected function _rewind(){
		return $this->_idResult->reset();
	}
	public function rowCount(){
		return -1;
	}
	public function bindColumn($column,&$param,$type=null)
	{throw new jException('jelix~db.error.feature.unsupported',array('sqlite3','bindColumn'));}
	public function bindParam($parameter,&$variable,$data_type=null,$length=null,$driver_options=null)
	{throw new jException('jelix~db.error.feature.unsupported',array('sqlite3','bindParam'));}
	public function bindValue($parameter,$value,$data_type)
	{throw new jException('jelix~db.error.feature.unsupported',array('sqlite3','bindValue'));}
	public function columnCount()
	{return $this->_idResult->numColumns();}
	public function execute($parameters=null)
	{throw new jException('jelix~db.error.feature.unsupported',array('sqlite3','bindColumn'));}
}
