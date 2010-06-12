<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jFormsDataContainer{
	public $data=array();
	public $originalData=array();
	public $privateData=array();
	public $formId;
	public $formSelector;
	public $errors=array();
	public $updatetime=0;
	public $token='';
	protected $readOnly=array();
	protected $deactivated=array();
	function __construct($formSelector,$formId){
		$this->formId=$formId;
		$this->formSelector=$formSelector;
	}
	function unsetData($name){
		unset($this->data[$name]);
	}
	function clear(){
		$this->data=array();
		$this->errors=array();
		$this->originalData=array();
		$this->privateData=array();
	}
	public function deactivate($name,$deactivation=true){
		if($deactivation){
			$this->deactivated[$name]=true;
		}
		else{
			if(isset($this->deactivated[$name]))
				unset($this->deactivated[$name]);
		}
	}
	public function setReadOnly($name,$readonly=true){
		if($readonly){
			$this->readOnly[$name]=true;
		}
		else{
			if(isset($this->readOnly[$name]))
				unset($this->readOnly[$name]);
		}
	}
	public function isActivated($name){
		return !isset($this->deactivated[$name]);
	}
	public function isReadOnly($name){
		return isset($this->readOnly[$name]);
	}
}
