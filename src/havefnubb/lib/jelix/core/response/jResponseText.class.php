<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @copyright   2005-2010 Laurent Jouanneau
* @copyright   2017 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseText extends jResponse{
	protected $_type='text';
	public $content='';
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->addHttpHeader('Content-Type','text/plain;charset='.jApp::config()->charset,false);
		$this->sendHttpHeaders();
		echo $this->content;
		return true;
	}
	public function outputErrors(){
		header("HTTP/1.0 500 Internal Jelix Error");
		header('Content-Type: text/plain;charset='.jApp::config()->charset);
		echo jApp::coord()->getGenericErrorMessage();
	}
}
