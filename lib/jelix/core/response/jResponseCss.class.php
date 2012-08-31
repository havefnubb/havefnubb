<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Nicolas Jeudy
* @contributor Laurent Jouanneau
* @copyright   2006 Nicolas Jeudy
* @copyright   2007-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseCss extends jResponse{
	protected $_type='css';
	public $content='';
	public function output(){
		global $gJConfig;
		$this->_httpHeaders['Content-Type']='text/css;charset='.$gJConfig->charset;
		$this->_httpHeaders['Content-length']=strlen($this->content);
		$this->sendHttpHeaders();
		echo $this->content;
		return true;
	}
}
