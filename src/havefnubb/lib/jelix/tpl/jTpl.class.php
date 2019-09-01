<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @copyright   2005-2012 Laurent Jouanneau, 2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jTpl{
	public $_vars=array();
	public $_privateVars=array();
	public $_meta=array();
	public function __construct(){
		$config=jApp::config();
		$this->_vars['j_basepath']=$config->urlengine['basePath'];
		$this->_vars['j_jelixwww']=$config->urlengine['jelixWWWPath'];
		$this->_vars['j_jquerypath']=$config->urlengine['jqueryPath'];
		$this->_vars['j_themepath']=$config->urlengine['basePath'].'themes/'.$config->theme.'/';
		$this->_vars['j_locale']=$config->locale;
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
		$sel=new jSelectorTpl($tpl,$outputtype,$trusted);
		$tpl=$sel->toString();
		if(in_array($tpl,$this->processedMeta)){
			return $this->_meta;
		}
		$this->processedMeta[]=$tpl;
		$md=$this->getTemplate($sel,$outputtype,$trusted);
		$fct='template_meta_'.$md;
		$fct($this);
		return $this->_meta;
	}
	public function display($tpl,$outputtype='',$trusted=true){
		$sel=new jSelectorTpl($tpl,$outputtype,$trusted);
		$tpl=$sel->toString();
		$previousTpl=$this->_templateName;
		$this->_templateName=$tpl;
		$this->recursiveTpl[]=$tpl;
		$md=$this->getTemplate($sel,$outputtype,$trusted);
		$fct='template_'.$md;
		$fct($this);
		array_pop($this->recursiveTpl);
		$this->_templateName=$previousTpl;
	}
	public $_templateName;
	protected $recursiveTpl=array();
	protected $processedMeta=array();
	protected function getTemplate($tpl,$outputtype='',$trusted=true){
		$tpl->userModifiers=$this->userModifiers;
		$tpl->userFunctions=$this->userFunctions;
		jIncluder::inc($tpl);
		return md5($tpl->module.'_'.$tpl->resource.'_'.$tpl->outputType.($trusted?'_t':''));
	}
	public function fetch($tpl,$outputtype='',$trusted=true,$callMeta=true){
		$content='';
		ob_start();
		try{
			$sel=new jSelectorTpl($tpl,$outputtype,$trusted);
			$tpl=$sel->toString();
			$previousTpl=$this->_templateName;
			$this->_templateName=$tpl;
			if($callMeta){
				if(in_array($tpl,$this->processedMeta)){
					$callMeta=false;
				}
				else
					$this->processedMeta[]=$tpl;
			}
			$this->recursiveTpl[]=$tpl;
			$md=$this->getTemplate($sel,$outputtype,$trusted);
			if($callMeta){
				$fct='template_meta_'.$md;
				$fct($this);
			}
			$fct='template_'.$md;
			$fct($this);
			array_pop($this->recursiveTpl);
			$this->_templateName=$previousTpl;
			$content=ob_get_clean();
		}catch(Exception $e){
			ob_end_clean();
			throw $e;
		}
		return $content;
	}
	public function fetchFromString($tpl,$outputtype='',$trusted=true,$callMeta=true){
		$content='';
		ob_start();
		try{
			$cachePath=jApp::tempPath('compiled/templates/virtuals/');
			require_once(JELIX_LIB_PATH.'tpl/jTplCompiler.class.php');
			$previousTpl=$this->_templateName;
			$md='virtual_'.md5($tpl).($trusted?'_t':'');
			$this->_templateName=$md;
			if($outputtype=='')
				$outputtype='html';
			$cachePath.=$outputtype.'_'.$this->_templateName.'.php';
			$mustCompile=jApp::config()->compilation['force']||!file_exists($cachePath);
			if($mustCompile&&!function_exists('template_'.$md)){
				$compiler=new jTplCompiler();
				$compiler->outputType=$outputtype;
				$compiler->trusted=$trusted;
				$compiler->compileString($tpl,$cachePath,$this->userModifiers,$this->userFunctions,$md);
			}
			require_once($cachePath);
			if($callMeta){
				$fct='template_meta_'.$md;
				$fct($this);
			}
			$fct='template_'.$md;
			$fct($this);
			$content=ob_get_clean();
			$this->_templateName=$previousTpl;
		}catch(exception $e){
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
		return jApp::config()->charset;
	}
}
