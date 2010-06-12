<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @copyright   2006-2007 Laurent Jouanneau
* @copyright   2008 Dominique Papin
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
interface jIFormsDatasource{
	public function getData($form);
	public function getLabel($key);
}
class jFormsStaticDatasource implements jIFormsDatasource{
	public $data=array();
	public function getData($form){
		return $this->data;
	}
	public function getLabel($key){
		if(isset($this->data[$key]))
			return $this->data[$key];
		else
			return null;
	}
}
class jFormsDaoDatasource implements jIFormsDatasource{
	protected $selector;
	protected $method;
	protected $labelProperty=array();
	protected $labelSeparator;
	protected $keyProperty;
	protected $profile;
	protected $criteria=null;
	protected $criteriaFrom=null;
	protected $dao=null;
	function __construct($selector,$method,$label,$key,$profile='',$criteria=null,$criteriaFrom=null,$labelSeparator=''){
		$this->selector=$selector;
		$this->profile=$profile;
		$this->method=$method;
		$this->labelProperty=preg_split('/[\s,]+/',$label);
		$this->labelSeparator=$labelSeparator;
		if($criteria!==null)
			$this->criteria=preg_split('/[\s,]+/',$criteria);
		if($criteriaFrom!==null)
			$this->criteriaFrom=preg_split('/[\s,]+/',$criteriaFrom);
		if($key==''){
			$rec=jDao::createRecord($this->selector,$this->profile);
			$pfields=$rec->getPrimaryKeyNames();
			$key=$pfields[0];
		}
		$this->keyProperty=$key;
	}
	public function getData($form){
		if($this->dao===null)
			$this->dao=jDao::get($this->selector,$this->profile);
		if($this->criteria!==null){
			$found=call_user_func_array(array($this->dao,$this->method),$this->criteria);
		}else if($this->criteriaFrom!==null){
			$args=array();
			foreach((array)$this->criteriaFrom as $criteria){
			array_push($args,$form->getData($criteria));
			}
			$found=call_user_func_array(array($this->dao,$this->method),$args);
		}else{
			$found=$this->dao->{$this->method}();
		}
		$result=array();
		foreach($found as $obj){
			$label='';
			foreach((array)$this->labelProperty as $property){
				if(!empty($obj->{$property}))
					$label.=$obj->{$property}.$this->labelSeparator;
			}
			if($this->labelSeparator!='')
				$label=substr($label,0,-strlen($this->labelSeparator));
			$result[$obj->{$this->keyProperty}]=$label;
		}
		return $result;
	}
	public function getLabel($key){
		if($this->dao===null)$this->dao=jDao::get($this->selector,$this->profile);
		$rec=$this->dao->get($key);
		if($rec){
			$label='';
			foreach((array)$this->labelProperty as $property){
				if(!empty($rec->{$property}))
					$label.=$rec->{$property}.$this->labelSeparator;
			}
			if($this->labelSeparator!='')
				$label=substr($label,0,-strlen($this->labelSeparator));
			return $label;
		}
		else
			return null;
	}
}
