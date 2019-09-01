<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Nicolas Jeudy
* @contributor Laurent Jouanneau, Julien Issler
* @copyright   2006 Nicolas Jeudy
* @copyright   2007-2012 Laurent Jouanneau
* @copyright   2017 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseCss extends jResponse{
	protected $_type='css';
	public $content='';
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']='text/css;charset='.jApp::config()->charset;
		$this->sendHttpHeaders();
		echo $this->content;
		return true;
	}
}
