<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @copyright  2010-2017 Laurent Jouanneau
*
* @link        http://jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbIndex{
	public $name;
	public $type;
	public $columns=array();
	public $isUnique=false;
	function __construct($name,$type='',$columns=array()){
		$this->name=$name;
		$this->columns=$columns;
		$this->type=$type;
	}
}
abstract class jDbConstraint{
	public $name;
	public $columns=array();
	function __construct($name,$columns){
		$this->name=$name;
		if(is_string($columns)){
			$this->columns=array($columns);
		}
		else{
			$this->columns=$columns;
		}
	}
}
class jDbUniqueKey extends jDbConstraint{
	function __construct($name,$columns=null){
		if($columns===null){
			parent::__construct($name,array());
		}
		else{
			parent::__construct($name,$columns);
		}
	}
}
class jDbPrimaryKey extends jDbConstraint{
	function __construct($columns,$name=''){
		parent::__construct($name,$columns);
	}
}
class jDbReference  extends jDbConstraint{
	public $name;
	public $columns=array();
	public $fTable='';
	public $fColumns=array();
	public $onUpdate='';
	public $onDelete='';
	function __construct($name='',$columns=array(),$foreignTable='',$foreignColumns=array()){
		parent::__construct($name,$columns);
		$this->fTable=$foreignTable;
		if(is_string($foreignColumns)){
			$this->fColumns=array($foreignColumns);
		}
		else{
			$this->fColumns=$foreignColumns;
		}
	}
}
class jDbColumn{
	public $type;
	public $nativeType;
	public $name;
	public $notNull=false;
	public $autoIncrement=false;
	public $default=null;
	public $hasDefault=false;
	public $length=0;
	public $precision=0;
	public $scale=0;
	public $sequence=false;
	public $unsigned=false;
	public $minLength=null;
	public $maxLength=null;
	public $minValue=null;
	public $maxValue=null;
	public $comment='';
	function __construct($name,$type,$length=0,$hasDefault=false,
						$default=null,$notNull=false){
		$this->type=$type;
		$this->name=$name;
		$this->length=$length;
		$this->hasDefault=$hasDefault;
		if($hasDefault){
			$this->default=($notNull&&$default===null?'':$default);
		}
		else{
			$this->default=($notNull?'':null);
		}
		$this->notNull=$notNull;
	}
	function isEqualTo($column){
		return(
		$this->name==$column->name&&
		$this->type==$column->type&&
		$this->notNull==$column->notNull&&
		$this->autoIncrement==$column->autoIncrement&&
		$this->default==$column->default&&
		$this->hasDefault==$column->hasDefault&&
		$this->length==$column->length&&
		$this->scale==$column->scale&&
		$this->sequence==$column->sequence&&
		$this->unsigned==$column->unsigned
		);
	}
	function hasOnlyDifferentName($otherColumn){
		return(
			$this->name!=$otherColumn->name&&
			$this->type==$otherColumn->type&&
			$this->notNull==$otherColumn->notNull&&
			$this->autoIncrement==$otherColumn->autoIncrement&&
			$this->default==$otherColumn->default&&
			$this->hasDefault==$otherColumn->hasDefault&&
			$this->length==$otherColumn->length&&
			$this->scale==$otherColumn->scale&&
			$this->sequence==$otherColumn->sequence&&
			$this->unsigned==$otherColumn->unsigned
		);
	}
}
