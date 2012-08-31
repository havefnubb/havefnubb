<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Tahina Ramaroson
* @contributor Sylvain de Vathaire, Dominique Papin, Olivier Demah, Laurent Jouanneau
* @copyright   2008 Tahina Ramaroson, Sylvain de Vathaire
* @copyright   2008 Dominique Papin
* @copyright   2009 Olivier Demah, 2009-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseHtmlFragment extends jResponse{
	protected $_type='htmlfragment';
	public $tplname='';
	public $tpl=null;
	protected $_contentTop=array();
	protected $_contentBottom=array();
	function __construct(){
		$this->tpl=new jTpl();
		parent::__construct();
	}
	final public function output(){
		global $gJConfig;
		$this->doAfterActions();
		$content=implode("\n",$this->_contentTop);
		if($this->tplname!=''){
			$content.=$this->tpl->fetch($this->tplname,'html');
		}
		$content.=implode("\n",$this->_contentBottom);
		$this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;
		$this->_httpHeaders['Content-length']=strlen($content);
		$this->sendHttpHeaders();
		echo $content;
		return true;
	}
	function addContent($content,$beforeTpl=false){
	if($beforeTpl){
		$this->_contentTop[]=$content;
	}else{
		$this->_contentBottom[]=$content;
	}
	}
	protected function doAfterActions(){
	}
	final public function outputErrors(){
		global $gJConfig;
		$this->clearHttpHeaders();
		$this->_httpStatusCode='500';
		$this->_httpStatusMsg='Internal Jelix Error';
		$this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;
		$content='<p class="htmlfragmenterror">';
		$content.=htmlspecialchars($GLOBALS['gJCoord']->getGenericErrorMessage());
		$content.='</p>';
		$this->_httpHeaders['Content-length']=strlen($content);
		$this->sendHttpHeaders();
		echo $content;
	}
	public function getFormatType(){return 'html';}
}
