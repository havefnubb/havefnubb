<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Loic Mathaud, Julien Issler
* @copyright   2006-2011 Laurent Jouanneau
* @copyright   2007-2008 Loic Mathaud
* @copyright   2017 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseJson extends jResponse{
	public $data=null;
	public $options=0;
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']="application/json";
		$content=json_encode($this->data,$this->options);
		$this->sendHttpHeaders();
		echo $content;
		return true;
	}
	public function outputErrors(){
		$message=array();
		$message['errorMessage']=jApp::coord()->getGenericErrorMessage();
		$e=jApp::coord()->getErrorMessage();
		if($e){
			$message['errorCode']=$e->getCode();
		}else{
			$message['errorCode']=-1;
		}
		$this->clearHttpHeaders();
		$this->_httpStatusCode='500';
		$this->_httpStatusMsg='Internal Server Error';
		$this->_httpHeaders['Content-Type']="application/json";
		$content=json_encode($message);
		$this->sendHttpHeaders();
		echo $content;
	}
}
