<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage core_response
* @author     Yannick Le Guédart
* @contributor Laurent Jouanneau
* @copyright  2006 Yannick Le Guédart
* @copyright  2006-2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
abstract class jResponseXMLFeed extends jResponse{
	public $charset;
	public $lang;
	public $infos=null;
	public $itemList=array();
	private $_template=null;
	private $_mainTpl='';
	private $_xsl=array();
	function __construct(){
		$this->charset=jApp::config()->charset;
		$this->lang=jLocale::getCurrentLang();
		parent::__construct();
	}
	abstract public function createItem($title,$link,$date);
	public function addItem($item){
		$this->itemList[]=$item;
	}
	public function addOptionals($content){
		if(is_array($content)){
			$this->_optionals=$content;
		}
	}
	public function addXSLStyleSheet($src,$params=array()){
		if(!isset($this->_xsl[$src])){
			$this->_xsl[$src]=$params;
		}
	}
	protected function _outputXmlHeader(){
		foreach($this->_xsl as $src=>$params){
			$more='';
			foreach($params as $param_name=>$param_value){
				$more.=$param_name.'="'. htmlspecialchars($param_value).'" ';
			}
			echo ' <?xml-stylesheet type="text/xsl" href="',$src,'" ',$more,' ?>';
		}
	}
	protected function _outputOptionals(){
		if(is_array($this->_optionals)){
			foreach($this->_optionals as $name=>$value){
				echo '<'. $name .'>'. $value .'</'. $name .'>',"\n";
			}
		}
	}
}
