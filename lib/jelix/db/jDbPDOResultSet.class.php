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
	protected $_fetchMode=0;
	public function fetch($fetch_style=null,$cursor_orientation=PDO::FETCH_ORI_NEXT,$cursor_offset=0){
		if($fetch_style){
			$rec=parent::fetch($fetch_style,$cursor_orientation,$cursor_offset);
		}
		else{
			$rec=parent::fetch();
		}
		if($rec&&count($this->modifier)){
			foreach($this->modifier as $m)
				call_user_func_array($m,array($rec,$this));
		}
		return $rec;
	}
	public function fetchAll($fetch_style=null,$fetch_argument=null,$ctor_arg=null){
		$final_style=($fetch_style ?: $this->_fetchMode);
		if(!$final_style){
			$records=parent::fetchAll(PDO::FETCH_OBJ);
		}
		else if($ctor_arg){
			$records=parent::fetchAll($final_style,$fetch_argument,$ctor_arg);
		}
		else if($fetch_argument){
			$records=parent::fetchAll($final_style,$fetch_argument);
		}
		else{
			$records=parent::fetchAll($final_style);
		}
		if(count($this->modifier)){
			foreach($records as $rec)
				foreach($this->modifier as $m)
					call_user_func_array($m,array($rec,$this));
		}
		return $records;
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
