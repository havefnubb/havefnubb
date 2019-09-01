<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Loic Mathaud
* @contributor Laurent Jouanneau
* @contributor Sylvain de Vathaire
* @contributor Thomas Pellissier Tanon
* @copyright   2005-2006 loic Mathaud
* @copyright   2007-2010 Laurent Jouanneau
* @copyright   2008 Sylvain de Vathaire
* @copyright   2011 Thomas Pellissier Tanon
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
class jResponseXml extends jResponse{
	protected $_type='xml';
	public $content=null;
	public $contentTpl='';
	public $checkValidity=false;
	protected $_charset;
	private $_css=array();
	private $_xsl=array();
	public $sendXMLHeader=TRUE;
	function __construct(){
		$this->_charset=jApp::config()->charset;
		$this->content=new jTpl();
		parent::__construct();
	}
	final public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		if(!array_key_exists('Content-Type',$this->_httpHeaders)){
			$this->_httpHeaders['Content-Type']='text/xml;charset='.$this->_charset;
		}
		if(is_string($this->content)){
			$xml_string=$this->content;
		}else if(!empty($this->contentTpl)){
			$xml_string=$this->content->fetch($this->contentTpl);
		}else{
			throw new jException('jelix~errors.repxml.no.content');
		}
		if($this->checkValidity){
			if(!simplexml_load_string($xml_string)){
				throw new jException('jelix~errors.repxml.invalid.content');
			}
		}
		$this->sendHttpHeaders();
		if($this->sendXMLHeader){
			echo '<?xml version="1.0" encoding="'. $this->_charset .'"?>',"\n";
			$this->outputXmlHeader();
		}
		echo $xml_string;
		return true;
	}
	final public function outputErrors(){
		header("HTTP/1.0 500 Internal Jelix Error");
		header('Content-Type: text/plain;charset='.jApp::config()->charset);
		echo jApp::coord()->getGenericErrorMessage();
	}
	public function addCSSStyleSheet($src,$params=array()){
		if(!isset($this->_css[$src])){
			$this->_css[$src]=$params;
		}
	}
	public function addXSLStyleSheet($src,$params=array()){
		if(!isset($this->_xsl[$src])){
			$this->_xsl[$src]=$params;
		}
	}
	protected function outputXmlHeader(){
		foreach($this->_xsl as $src=>$params){
			$more='';
			foreach($params as $param_name=>$param_value){
				$more.=$param_name.'="'. htmlspecialchars($param_value).'" ';
			}
			echo ' <?xml-stylesheet type="text/xsl" href="',$src,'" ',$more,' ?>';
		}
		foreach($this->_css as $src=>$params){
			$more='';
			foreach($params as $param_name=>$param_value){
				$more.=$param_name.'="'. htmlspecialchars($param_value).'" ';
			}
			echo ' <?xml-stylesheet type="text/css" href="',$src,'" ',$more,' ?>';
		}
	}
}
