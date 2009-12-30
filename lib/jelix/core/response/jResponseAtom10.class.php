<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Yannick Le Guédart
* @contributor Laurent Jouanneau
* @copyright   2006 Yannick Le Guédart
* @copyright   2006-2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
require_once(JELIX_LIB_CORE_PATH.'response/jResponseXmlFeed.class.php');
class jResponseAtom10 extends jResponseXMLFeed{
	protected $_type = 'atom1.0';
	function __construct(){
		$this->_template	 = new jTpl();
		$this->_mainTpl	 = 'jelix~atom10';
		$this->infos = new jAtom10Info();
		parent::__construct();
	}
	final public function output(){
		$this->_headSent = false;
		$this->_httpHeaders['Content-Type'] =
				'application/atom+xml;charset=' . $this->charset;
		$this->sendHttpHeaders();
		echo '<?xml version="1.0" encoding="'. $this->charset .'"?>', "\n";
		$this->_outputXmlHeader();
		$this->_headSent = true;
		if(!$this->infos->updated){
			$this->infos->updated = date("Y-m-d H:i:s");
		}
		$this->_template->assign('atom', $this->infos);
		$this->_template->assign('items', $this->itemList);
		$this->_template->assign('lang',$this->lang);
		$this->_template->display($this->_mainTpl);
		if($this->hasErrors()){
			echo $this->getFormatedErrorMsg();
		}
		echo '</feed>';
		return true;
	}
	final public function outputErrors(){
		if(!$this->_headSent){
			if(!$this->_httpHeadersSent){
				header("HTTP/1.0 500 Internal Server Error");
				header('Content-Type: text/xml;charset='.$this->charset);
			}
			echo '<?xml version="1.0" encoding="'. $this->charset .'"?>';
		}
		echo '<errors xmlns="http://jelix.org/ns/xmlerror/1.0">';
		if($this->hasErrors()){
			echo $this->getFormatedErrorMsg();
		} else{
			echo '<error>Unknow Error</error>';
		}
		echo '</errors>';
	}
	protected function getFormatedErrorMsg(){
		$errors = '';
		foreach($GLOBALS['gJCoord']->errorMessages  as $e){
		   $errors .=  '<error xmlns="http://jelix.org/ns/xmlerror/1.0" type="'. $e[0] .'" code="'. $e[1] .'" file="'. $e[3] .'" line="'. $e[4] .'">'.htmlentities($e[2], ENT_NOQUOTES, $this->charset). '</error>'. "\n";
		}
		return $errors;
	}
	public function createItem($title,$link, $date){
		$item = new jAtom10Item();
		$item->title = $title;
		$item->id = $item->link = $link;
		$item->published = $date;
		return $item;
	}
}
class jAtom10Info extends jXMLFeedInfo{
	public $id;
	public $selfLink;
	public $authors = array();
	public $otherLinks = array();
	public $contributors= array();
	public $icon;
	public $generatorVersion;
	public $generatorUrl;
	function __construct()
	{
		$this->_mandatory = array('title', 'id', 'updated');
	}
}
class jAtom10Item extends jXMLFeedItem{
	public $authorUri;
	public $otherAuthors = array();
	public $contributors= array();
	public $otherLinks= array();
	public $summary;
	public $summaryType;
	public $source;
	public $copyright;
	public $updated;
}