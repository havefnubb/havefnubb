<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Christophe Thiriot
* @contributor Laurent Jouanneau
* @copyright   2008 Christophe Thiriot, 2008-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jResponseCmdline extends jResponse{
	const EXIT_CODE_OK=0;
	const EXIT_CODE_ERROR=1;
	protected $_type='cmdline';
	protected $_buffer='';
	protected $_exit_code=self::EXIT_CODE_OK;
	public function output(){
		$this->flushContent();
		return true;
	}
	public function addContent($content,$bufferize=false){
		if($bufferize){
			$this->_buffer.=$content;
		}else{
			$this->flushContent();
			echo $content;
		}
	}
	public function flushContent(){
		echo $this->_buffer;
		$this->_buffer='';
	}
	public function getExitCode(){
		return $this->_exit_code;
	}
	public function setExitCode($code){
		$this->_exit_code=$code;
	}
	public function outputErrors(){
		$this->flushContent();
		foreach(jApp::coord()->allErrorMessages as $msg)
			fwrite(STDERR,$msg->getFormatedMessage()."\n");
		$this->setExitCode(self::EXIT_CODE_ERROR);
	}
	protected function sendHttpHeaders(){}
}
