<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author     Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Julien Issler, Yannick Le Guédart, Philippe Villiers
* @copyright  2001-2005 CopixTeam, 2005-2009 Laurent Jouanneau
* @copyright  2008 Thomas
* @copyright  2008 Julien Issler, 2009 Yannick Le Guédart
* @copyright  2013 Philippe Villiers
* This classes was get originally from the Copix project (CopixDAOSearchConditions, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this classes was adapted for Jelix by Laurent Jouanneau
*
* @link     http://jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDaoCondition{
	public $parent=null;
	public $conditions=array();
	public $group=array();
	public $glueOp;
	function __construct($glueOp='AND',$parent=null){
		$this->parent=$parent;
		$this->glueOp=$glueOp;
	}
	public function isEmpty(){
		return empty($this->conditions)&&empty($this->group);
	}
}
class jDaoConditions{
	public $condition;
	public $order=array();
	public $group=array();
	private $_currentCondition;
	function __construct($glueOp='AND'){
		$this->condition=new jDaoCondition($glueOp);
		$this->_currentCondition=$this->condition;
	}
	function addItemOrder($field_id,$way='ASC',$allowAnyWay=false){
		if(!$allowAnyWay&&strtoupper($way)!='DESC'&&strtoupper($way)!='ASC')
			throw new jException('jelix~dao.error.bad.operator',$way);
		$this->order[$field_id]=$way;
	}
	function addItemGroup($field_id){
		$this->group[]=$field_id;
	}
	function isEmpty(){
		return(count($this->condition->group)==0)&&
		(count($this->condition->conditions)==0)&&
		(count($this->order)==0);
	}
	function hasConditions(){
		return(count($this->condition->group)||count($this->condition->conditions));
	}
	function startGroup($glueOp='AND'){
		$glueOp=strtoupper($glueOp);
		if($glueOp!='AND'&&$glueOp!='OR')
			throw new jException('jelix~dao.error.bad.operator',$glueOp);
		$cond=new jDaoCondition($glueOp,$this->_currentCondition);
		$this->_currentCondition=$cond;
	}
	function endGroup(){
		if($this->_currentCondition->parent!==null){
			if(!$this->_currentCondition->isEmpty())
				$this->_currentCondition->parent->group[]=$this->_currentCondition;
			$this->_currentCondition=$this->_currentCondition->parent;
		}
	}
	function addCondition($field_id,$operator,$value,$field_pattern='%s',$foo=false){
		$operator=trim(strtoupper($operator));
		if(preg_match('/^[^\w\d\s;\(\)]+$/',$operator)||
			in_array($operator,array('LIKE','NOT LIKE','ILIKE','IN','NOT IN',
				'IS','IS NOT','IS NULL','IS NOT NULL','MATCH','REGEXP',
				'NOT REGEXP','~','!~','~*','!~*','RLIKE','SOUNDS LIKE',
				'BETWEEN'))
		){
			$this->_currentCondition->conditions[]=array(
				'field_id'=>$field_id,
				'field_pattern'=>$field_pattern,
				'value'=>$value,
				'operator'=>$operator,'isExpr'=>$foo);
		}
		else
			throw new jException('jelix~dao.error.bad.operator',$operator);
	}
}
