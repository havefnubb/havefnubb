<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Sylvain de Vathaire
* @contributor Laurent Jouanneau
* @copyright   2008 Sylvain de Vathaire, 2009-2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(LIB_PATH.'wshelper/WSDLStruct.class.php');
require_once(LIB_PATH.'wshelper/WSDLException.class.php');
require_once(LIB_PATH.'wshelper/WSException.class.php');
require_once(LIB_PATH.'wshelper/IPXMLSchema.class.php');
require_once(LIB_PATH.'wshelper/IPPhpDoc.class.php');
require_once(LIB_PATH.'wshelper/IPReflectionClass.class.php');
require_once(LIB_PATH.'wshelper/IPReflectionCommentParser.class.php');
require_once(LIB_PATH.'wshelper/IPReflectionMethod.class.php');
require_once(LIB_PATH.'wshelper/IPReflectionProperty.class.php');
class jWSDL{
	public $module;
	public $controller;
	private $controllerClassName;
	public $WSDLfilePath;
	private $_ctrlpath;
	private $_dirname='WSDL';
	private $_cacheSuffix='.wsdl';
	public function __construct($module,$controller){
		$this->module=$module;
		$this->controller=$controller;
		$this->_createPath();
		$this->_createCachePath();
	}
	private function _createPath(){
		$config=jApp::config();
		if(!isset($config->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.module.unknown',$this->module);
		}
		$this->_ctrlpath=$config->_modulesPathList[$this->module].'controllers/'.$this->controller.'.soap.php';
		if(!file_exists($this->_ctrlpath)){
			throw new jException('jelix~errors.action.unknown',$this->controller);
		}
		require_once($this->_ctrlpath);
		$this->controllerClassName=$this->controller.'Ctrl';
		if(!class_exists($this->controllerClassName,false)){
			throw new jException('jelix~errors.ad.controller.class.unknown',array('jWSDL',$this->controllerClassName,$this->_ctrlpath));
		}
		if(extension_loaded('eAccelerator')){
			$reflect=new ReflectionClass('jWSDL');
			if($reflect->getDocComment()==NULL){
				throw new jException('jWSDL~errors.eaccelerator.configuration');
			}
			unset($reflect);
		}
	}
	private function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/'.$this->_dirname.'/'.$this->module.'~'.$this->controller.$this->_cacheSuffix);
	}
	public function getWSDLFilePath(){
		$this->_updateWSDL();
		return $this->_cachePath;
	}
	public function getWSDLFile(){
		$this->_updateWSDL();
		return file_get_contents($this->_cachePath);
	}
	public function getOperationParams($operationName){
		$IPReflectionMethod=new IPReflectionMethod($this->controllerClassName,$operationName);
		return $IPReflectionMethod->parameters;
	}
	private function _updateWSDL(){
		static $updated=FALSE;
		if($updated){
			return;
		}
		$mustCompile=jApp::config()->compilation['force']||!file_exists($this->_cachePath);
		if(jApp::config()->compilation['checkCacheFiletime']&&!$mustCompile){
			if(filemtime($this->_ctrlpath)> filemtime($this->_cachePath)){
				$mustCompile=true;
			}
		}
		if($mustCompile){
			jFile::write($this->_cachePath,$this->_compile());
		}
		$updated=TRUE;
	}
	private function _compile(){
		$url=jUrl::get($this->module.'~'.$this->controller.':index@soap',array(),jUrl::JURL);
		$url->clearParam();
		$url->setParam('service',$this->module.'~'.$this->controller);
		$serverUri=jUrl::getRootUrlRessourceValue('soap');
		if($serverUri===null){
			$serverUri=jUrl::getRootUrlRessourceValue('soap-'.$this->module);
		}
		if($serverUri===null){
			$serverUri=jUrl::getRootUrlRessourceValue('soap-'.$this->module.'-'.$this->controller);
		}
		if($serverUri===null){
			$serverUri=jApp::coord()->request->getServerURI();
		}
		$serviceURL=$serverUri . $url->toString();
		$serviceNameSpace=$serverUri . jApp::config()->urlengine['basePath'];
		$wsdl=new WSDLStruct($serviceNameSpace,$serviceURL,SOAP_RPC,SOAP_ENCODED);
		$wsdl->setService(new IPReflectionClass($this->controllerClassName));
		try{
			$gendoc=$wsdl->generateDocument();
		}catch(WSDLException $exception){
			throw new JException('jWSDL~errors.wsdl.generation',$exception->msg);
		}
		return $gendoc;
	}
	public function doc($className=""){
		if($className!=""){
			if(!class_exists($className,false)){
				throw new jException('jelix~errors.ad.controller.class.unknown',array('WSDL generation',$className,$this->_ctrlpath));
			}
			$classObject=new IPReflectionClass($className);
		}else{
			$classObject=new IPReflectionClass($this->controllerClassName);
		}
		$documentation=Array();
		$documentation['menu']=Array();
		if($classObject){
			$classObject->properties=$classObject->getProperties(false,false,false);
			$classObject->methods=$classObject->getMethods(false,false,false);
			foreach((array)$classObject->methods as $method){
				$method->params=$method->getParameters();
			}
			$documentation['class']=$classObject;
			$documentation['service']=$this->module.'~'.$this->controller;
		}
		return $documentation;
	}
	public static function getSoapControllers(){
		$modules=jApp::config()->_modulesPathList;
		$controllers=array();
		foreach($modules as $module){
			if(is_dir($module.'controllers')){
				if($handle=opendir($module.'controllers')){
					$moduleName=basename($module);
					while(false!==($file=readdir($handle))){
						if(substr($file,strlen($file)- strlen('.soap.php'))=='.soap.php'){
							$controller=array();
							$controller['class']=substr($file,0,strlen($file)- strlen('.soap.php'));
							$controller['module']=$moduleName;
							$controller['service']=$moduleName.'~'.$controller['class'];
							array_push($controllers,$controller);
						}
					}
					closedir($handle);
				}
			}
		}
		return $controllers;
	}
}
