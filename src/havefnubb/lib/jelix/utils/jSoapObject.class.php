<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @copyright   2011-2012 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jSoapObject{
	function __construct($data=null){
		if($data){
			if($data instanceof \jFormsBase){
				$data=$data->getAllData();
			}
			$this->_initFromArray($data);
		}
	}
	public function _initFromArray(&$data){
		foreach($data as $key=>$value){
			$this->_setData($key,$value);
		}
	}
	public function _fillForm($form){
		$ar=get_object_vars($this);
		foreach($ar as $key=>$value){
			if($form->getControl($key))
				$form->setData($key,$this->_getData($key));
		}
	}
	function _setData($key,$value){
		$this->$key=$value;
	}
	function _getData($key){
		return $this->$key;
	}
	protected $_mapArray=array();
	protected $_vProp=array();
	public function __set($name,$value){
		if(in_array($name,$this->_mapArray)){
			if(is_array($value)){
				$this->_vProp[$name]=$value;
			}
			else
				$this->_vProp[$name]=array($value);
		}
		else{
			$this->$name=$value;
		}
	}
	public function __get($name){
		if(!isset($this->_vProp[$name])){
			if(in_array($name,$this->_mapArray))
				return($this->_vProp[$name]=array());
			return null;
		}
		return $this->_vProp[$name];
	}
	public function __isset($name){
		if(!isset($this->_vProp[$name])){
			if(in_array($name,$this->_mapArray)){
				$this->_vProp[$name]=array();
				return true;
			}
			return false;
		}
		return true;
	}
	public function __unset($name){
		unset($this->_vProp[$name]);
	}
}
