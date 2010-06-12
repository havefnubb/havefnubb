<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Laurent Jouanneau
* @contributor 
* @copyright  2010 Laurent Jouanneau
*
* @link        http://jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbIndex{
	public $name,$type;
	public $columns=array();
	function __construct($name,$type=''){
		$this->name=$name;
		$this->type=$type;
	}
}
class jDbUniqueKey extends jDbIndex{
}
class jDbPrimaryKey extends jDbIndex{
	function __construct($columns){
		if(is_string($columns))
			$this->columns=array($columns);
		else
			$this->columns=$columns;
	}
}
class jDbReference{
	public $name;
	public $columns=array();
	public $fTable='';
	public $fColumns=array();
	public $onUpdate='';
	public $onDelete='';
}
class jDbColumn{
	public $type;
	public $nativeType;
	public $name;
	public $notNull=true;
	public $autoIncrement=false;
	public $default='';
	public $hasDefault=false;
	public $length=0;
	public $sequence=false;
	public $unsigned=false;
	public $minLength=null;
	public $maxLength=null;
	public $minValue=null;
	public $maxValue=null;
	function __construct($name,$type,$length=0,$hasDefault=false,$default=null,$notNull=false){
		$this->type=$type;
		$this->name=$name;
		$this->length=$length;
		$this->hasDefault=$hasDefault;
		if($hasDefault){
			$this->default=$default;
		}
		else{
			$this->default='';
		}
		$this->notNull=$notNull;
	}
}
