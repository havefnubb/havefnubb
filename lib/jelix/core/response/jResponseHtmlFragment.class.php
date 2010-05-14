<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Tahina Ramaroson
* @contributor Sylvain de Vathaire, Dominique Papin, Olivier Demah
* @copyright   2008 Tahina Ramaroson, Sylvain de Vathaire
* @copyright   2008 Dominique Papin
* @copyright   2009 Olivier Demah
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseHtmlFragment extends jResponse{
	protected $_type = 'htmlfragment';
	public $tplname = '';
	public $tpl = null;
	protected $_contentTop = array();
	protected $_contentBottom = array();
	function __construct(){
		$this->tpl = new jTpl();
		parent::__construct();
	}
	final public function output(){
		global $gJConfig;
		if($this->hasErrors()) return false;
		$this->doAfterActions();
		if($this->hasErrors()) return false;
		$content = implode("\n",$this->_contentTop);
		if($this->tplname!=''){
			$content.=$this->tpl->fetch($this->tplname,'html');
			if($this->hasErrors()) return false;
		}
		$content .= implode("\n",$this->_contentBottom);
		$this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;
		$this->_httpHeaders['Content-length']=strlen($content);
		$this->sendHttpHeaders();
		echo $content;
		return true;
	}
	function addContent($content, $beforeTpl = false){
	  if($beforeTpl){
		$this->_contentTop[]=$content;
	  }else{
		 $this->_contentBottom[]=$content;
	  }
	}
	protected function doAfterActions(){
		$this->_commonProcess();
	}
	protected function _commonProcess(){
	}
	final public function outputErrors(){
		global $gJConfig;
		$this->clearHttpHeaders();
		$this->_httpStatusCode ='500';
		$this->_httpStatusMsg ='Internal Server Error';
		$this->_httpHeaders['Content-Type']='text/plain;charset='.$gJConfig->charset;
		if($this->hasErrors()){
			$content = $this->getFormatedErrorMsg();
		}else{
			$content = '<p style="color:#FF0000">Unknown Error</p>';
		}
		$this->_httpHeaders['Content-length'] = strlen($content);
		$this->sendHttpHeaders();
		echo $content;
	}
	protected function getFormatedErrorMsg(){
		global $gJConfig;
		$errors='';
		foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
		   $errors .=  '<p style="margin:0;"><b>['.$e[0].' '.$e[1].']</b> <span style="color:#FF0000">'.htmlspecialchars($e[2], ENT_NOQUOTES, $gJConfig->charset)."</span> \t".$e[3]." \t".$e[4]."</p>\n";
		}
		return $errors;
	}
	public function getFormatType(){ return 'html';}
}