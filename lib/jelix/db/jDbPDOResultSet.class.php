<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor Gwendal Jouannic, Thomas, Julien Issler
* @copyright  2005-2010 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic, 2009 Thomas
* @copyright  2009 Julien Issler
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbPDOResultSet extends PDOStatement{
	const FETCH_CLASS=8;
	protected $_fetchMode=0;
	public function fetch($fetch_style=PDO::FETCH_BOTH,$cursor_orientation=PDO::FETCH_ORI_NEXT,$cursor_offset=0){
		$rec=parent::fetch();
		if($rec&&count($this->modifier)){
			foreach($this->modifier as $m)
				call_user_func_array($m,array($rec,$this));
		}
		return $rec;
	}
	public function fetchAll($fetch_style=PDO::FETCH_OBJ,$column_index=0,$ctor_arg=null){
		if($this->_fetchMode){
			if($this->_fetchMode!=PDO::FETCH_COLUMN)
				return parent::fetchAll($this->_fetchMode);
			else
				return parent::fetchAll($this->_fetchMode,$column_index);
		}
		else{
			return parent::fetchAll(PDO::FETCH_OBJ);
		}
	}
	public function setFetchMode($mode,$arg1=null,$arg2=null){
		$this->_fetchMode=$mode;
		if($arg1===null)
			return parent::setFetchMode($mode);
		else if($arg2===null)
			return parent::setFetchMode($mode,$arg1);
		return parent::setFetchMode($mode,$arg1,$arg2);
	}
	public function unescapeBin($text){
		return $text;
	}
	protected $modifier=array();
	public function addModifier($function){
		$this->modifier[]=$function;
	}
}
