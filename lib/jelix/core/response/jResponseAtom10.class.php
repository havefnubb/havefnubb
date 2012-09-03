<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Yannick Le Guédart
* @contributor Laurent Jouanneau
* @copyright   2006 Yannick Le Guédart
* @copyright   2006-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
require_once(JELIX_LIB_CORE_PATH.'response/jResponseXmlFeed.class.php');
require_once(JELIX_LIB_PATH.'utils/jAtom10Info.class.php');
require_once(JELIX_LIB_PATH.'utils/jAtom10Item.class.php');
class jResponseAtom10 extends jResponseXMLFeed{
	protected $_type='atom1.0';
	function __construct(){
		$this->_template=new jTpl();
		$this->_mainTpl='jelix~atom10';
		$this->infos=new jAtom10Info();
		parent::__construct();
	}
	final public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']=
				'application/atom+xml;charset=' . $this->charset;
		if(!$this->infos->updated){
			$this->infos->updated=date("Y-m-d H:i:s");
		}
		$this->_template->assign('atom',$this->infos);
		$this->_template->assign('items',$this->itemList);
		$this->_template->assign('lang',$this->lang);
		$content=$this->_template->fetch($this->_mainTpl);
		$this->sendHttpHeaders();
		echo '<?xml version="1.0" encoding="'. $this->charset .'"?>',"\n";
		$this->_outputXmlHeader();
		echo $content;
		return true;
	}
	public function createItem($title,$link,$date){
		$item=new jAtom10Item();
		$item->title=$title;
		$item->id=$item->link=$link;
		$item->published=$date;
		return $item;
	}
}
