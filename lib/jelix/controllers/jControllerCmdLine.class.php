<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  controllers
* @author      Loic Mathaud
* @contributor M. Thiriot, Laurent Jouanneau
* @copyright   2006 Loic Mathaud, 2007 M. Thiriot, 2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class jControllerCmdLine extends jController{
	public $help = array();
	protected $allowed_options;
	protected $allowed_parameters;
	protected $_options;
	protected $_parameters;
	function __construct($request){
		if($request == null)
			return;
		$this->request = $request;
		$params = $this->request->params;
		unset($params['module']);
		unset($params['action']);
		$action = new jSelectorAct($this->request->params['action']);
		if( !in_array($action->method, get_class_methods(get_class($this)))){
			throw new jException('jelix~errors.cli.unknow.command', $action->method);
		}
		$opt = isset($this->allowed_options[$action->method]) ? $this->allowed_options[$action->method]: array();
		$par = isset($this->allowed_parameters[$action->method]) ? $this->allowed_parameters[$action->method]: array();
		list($this->_options,$this->_parameters) = jCmdUtils::getOptionsAndParams($params, $opt, $par);
	}
	protected function param($parName, $parDefaultValue=null, $useDefaultIfEmpty=false){
		if(isset($this->_parameters[$parName])){
			if($this->_parameters[$parName] == '' && $useDefaultIfEmpty)
				return $parDefaultValue;
			else
				return $this->_parameters[$parName];
		} else{
			return $parDefaultValue;
		}
	}
	protected function option($name){
		if(isset($this->_options[$name])){
			return $this->_options[$name];
		} else{
			return false;
		}
	}
}