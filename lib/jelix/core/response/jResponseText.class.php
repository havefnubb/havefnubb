<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @copyright   2005-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseText extends jResponse{
	protected $_type='text';
	public $content='';
	public function output(){
		global $gJConfig;
		$this->addHttpHeader('Content-Type','text/plain;charset='.$gJConfig->charset,false);
		$this->_httpHeaders['Content-length']=strlen($this->content);
		$this->sendHttpHeaders();
		echo $this->content;
		return true;
	}
	public function outputErrors(){
		global $gJConfig;
		header("HTTP/1.0 500 Internal Jelix Error");
		header('Content-Type: text/plain;charset='.$gJConfig->charset);
		echo $GLOBALS['gJCoord']->getGenericErrorMessage();
	}
}
