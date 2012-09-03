<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Loic Mathaud
* @author      Yannick Le Guédart
* @contributor Laurent Jouanneau
* @copyright   2005-2006 Loic Mathaud
* @copyright   2006 Yannick Le Guédart
* @copyright   2006-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
require_once(JELIX_LIB_CORE_PATH.'response/jResponseXmlFeed.class.php');
require_once(JELIX_LIB_PATH.'utils/jRSS20Info.class.php');
require_once(JELIX_LIB_PATH.'utils/jRSS20Item.class.php');
class jResponseRss20 extends jResponseXMLFeed{
	protected $_type='rss2.0';
	function __construct(){
		$this->_template=new jTpl();
		$this->_mainTpl='jelix~rss20';
		$this->infos=new jRSS20Info();
		parent::__construct();
		$this->infos->language=$this->lang;
	}
	final public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']=
				'application/xml;charset=' . $this->charset;
		$this->_template->assign('rss',$this->infos);
		$this->_template->assign('items',$this->itemList);
		$content=$this->_template->fetch($this->_mainTpl);
		$this->sendHttpHeaders();
		echo '<?xml version="1.0" encoding="'. $this->charset .'"?>',"\n";
		$this->_outputXmlHeader();
		echo $content;
		return true;
	}
	public function createItem($title,$link,$date){
		$item=new jRSS20Item();
		$item->title=$title;
		$item->id=$item->link=$link;
		$item->published=$date;
		return $item;
	}
}
