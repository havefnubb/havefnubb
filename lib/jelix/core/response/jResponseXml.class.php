<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Loic Mathaud
* @contributor Laurent Jouanneau
* @contributor Sylvain de Vathaire
* @copyright   2005-2006 loic Mathaud
* @copyright   2007 Laurent Jouanneau
* @copyright   2008 Sylvain de Vathaire
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
class jResponseXml extends jResponse{
	protected $_type = 'xml';
	public $content = null;
	public $contentTpl = '';
	protected $_charset;
	private $_css = array();
	private $_xsl = array();
	protected $_headSent = 0;
	public $sendXMLHeader = TRUE;
	function __construct(){
		global $gJConfig;
		$this->_charset = $gJConfig->charset;
		$this->content = new jTpl();
		parent::__construct();
	}
	final public function output(){
		$this->_httpHeaders['Content-Type']='text/xml;charset='.$this->_charset;
		$this->sendHttpHeaders();
		if($this->sendXMLHeader){
			echo '<?xml version="1.0" encoding="'. $this->_charset .'"?>', "\n";
			$this->outputXmlHeader();
		}
		$this->_headSent = true;
		if(is_string($this->content)){
			$xml_string = $this->content;
		}else if(!empty($this->contentTpl)){
			$xml_string = $this->content->fetch($this->contentTpl);
		}else{
			throw new jException('jelix~errors.repxml.no.content');
		}
		if(simplexml_load_string($xml_string)){
			echo $xml_string;
		} else{
			throw new jException('jelix~errors.repxml.invalid.content');
		}
		return true;
	}
	final public function outputErrors(){
		if(!$this->_headSent){
			if(!$this->_httpHeadersSent){
				header("HTTP/1.0 500 Internal Server Error");
				header('Content-Type: text/xml;charset='.$this->_charset);
			}
			echo '<?xml version="1.0" encoding="'. $this->_charset .'"?>';
		}
		echo '<errors xmlns="http://jelix.org/ns/xmlerror/1.0">';
		if($this->hasErrors()){
			foreach($GLOBALS['gJCoord']->errorMessages  as $e){
				echo '<error xmlns="http://jelix.org/ns/xmlerror/1.0" type="'. $e[0] .'" code="'. $e[1] .'" file="'. $e[3] .'" line="'. $e[4] .'">'.htmlspecialchars($e[2], ENT_NOQUOTES, $this->_charset). '</error>'. "\n";
			}
		} else{
			echo '<error>Unknown Error</error>';
		}
		echo '</errors>';
	}
	public function addCSSStyleSheet($src, $params = array()){
		if(!isset($this->_css[$src])){
			$this->_css[$src] = $params;
		}
	}
	public function addXSLStyleSheet($src, $params = array()){
		if(!isset($this->_xsl[$src])){
			$this->_xsl[$src] = $params;
		}
	}
	protected function outputXmlHeader(){
		foreach($this->_xsl as $src => $params){
			$more = '';
			foreach($params as $param_name => $param_value){
				$more .= $param_name.'="'. htmlspecialchars($param_value).'" ';
			}
			echo ' <?xml-stylesheet type="text/xsl" href="', $src,'" ', $more,' ?>';
		}
		foreach($this->_css as $src => $params){
			$more = '';
			foreach($params as $param_name => $param_value){
				$more .= $param_name.'="'. htmlspecialchars($param_value).'" ';
			}
			echo ' <?xml-stylesheet type="text/css" href="', $src,'" ', $more,' ?>';
		}
	}
}