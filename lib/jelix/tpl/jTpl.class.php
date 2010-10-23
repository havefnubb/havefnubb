<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @copyright   2005-2009 Laurent Jouanneau, 2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jTpl{
	public $_vars=array();
	public $_privateVars=array();
	public $_meta=array();
	public function __construct(){
		global $gJConfig;
		$this->_vars['j_basepath']=$gJConfig->urlengine['basePath'];
		$this->_vars['j_jelixwww']=$gJConfig->urlengine['jelixWWWPath'];
		$this->_vars['j_jquerypath']=$gJConfig->urlengine['jqueryPath'];
		$this->_vars['j_themepath']=$gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
		$this->_vars['j_locale']=$gJConfig->locale;
		$this->_vars['j_datenow']=date('Y-m-d');
		$this->_vars['j_timenow']=date('H:i:s');
	}
	public function assign($name,$value=null){
		if(is_array($name)){
			$this->_vars=array_merge($this->_vars,$name);
		}else{
			$this->_vars[$name]=$value;
		}
	}
	public function assignByRef($name,& $value){
		$this->_vars[$name]=&$value;
	}
	public function append($name,$value=null){
		if(is_array($name)){
			foreach($name as $key=>$val){
				if(isset($this->_vars[$key]))
					$this->_vars[$key].=$val;
				else
					$this->_vars[$key]=$val;
			}
		}else{
			if(isset($this->_vars[$name]))
				$this->_vars[$name].=$value;
			else
				$this->_vars[$name]=$value;
		}
	}
	public function assignIfNone($name,$value=null){
		if(is_array($name)){
			foreach($name as $key=>$val){
				if(!isset($this->_vars[$key]))
					$this->_vars[$key]=$val;
			}
		}else{
			if(!isset($this->_vars[$name]))
				$this->_vars[$name]=$value;
		}
	}
	function assignZone($name,$zoneName,$params=array()){
		$this->_vars[$name]=jZone::get($zoneName,$params);
	}
	function appendZone($name,$zoneName,$params=array()){
		if(isset($this->_vars[$name]))
			$this->_vars[$name].=jZone::get($zoneName,$params);
		else
			$this->_vars[$name]=jZone::get($zoneName,$params);
	}
	function assignZoneIfNone($name,$zoneName,$params=array()){
		if(!isset($this->_vars[$name]))
			$this->_vars[$name]=jZone::get($zoneName,$params);
	}
	public function isAssigned($name){
		return isset($this->_vars[$name]);
	}
	public function get($name){
		if(isset($this->_vars[$name])){
			return $this->_vars[$name];
		}else{
			$return=null;
			return $return;
		}
	}
	public function getTemplateVars(){
		return $this->_vars;
	}
	public function meta($tpl,$outputtype='',$trusted=true){
		$this->getTemplate($tpl,'template_meta_',$outputtype,$trusted);
		return $this->_meta;
	}
	public function display($tpl,$outputtype='',$trusted=true){
		$this->getTemplate($tpl,'template_',$outputtype,$trusted);
	}
	public $_templateName;
	protected function getTemplate($tpl,$fctname,$outputtype='',$trusted=true){
		$sel=new jSelectorTpl($tpl,$outputtype,$trusted);
		$sel->userModifiers=$this->userModifiers;
		$sel->userFunctions=$this->userFunctions;
		jIncluder::inc($sel);
		$this->_templateName=$sel->toString();
		$fct=$fctname.md5($sel->module.'_'.$sel->resource.'_'.$sel->outputType.($trusted?'_t':''));
		$fct($this);
	}
	public function fetch($tpl,$outputtype='',$trusted=true,$callMeta=true){
		$content='';
		ob_start();
		try{
			$sel=new jSelectorTpl($tpl,$outputtype,$trusted);
			$sel->userModifiers=$this->userModifiers;
			$sel->userFunctions=$this->userFunctions;
			jIncluder::inc($sel);
			$md=md5($sel->module.'_'.$sel->resource.'_'.$sel->outputType.($trusted?'_t':''));
			$this->_templateName=$sel->toString();
			if($callMeta){
				$fct='template_meta_'.$md;
				$fct($this);
			}
			$fct='template_'.$md;
			$fct($this);
			$content=ob_get_clean();
		}catch(Exception $e){
			ob_end_clean();
			throw $e;
		}
		return $content;
	}
	protected $userModifiers=array();
	public function registerModifier($name,$functionName){
		$this->userModifiers[$name]=$functionName;
	}
	protected $userFunctions=array();
	public function registerFunction($name,$functionName){
		$this->userFunctions[$name]=$functionName;
	}
	public static function getEncoding(){
		return $GLOBALS['gJConfig']->charset;
	}
}
