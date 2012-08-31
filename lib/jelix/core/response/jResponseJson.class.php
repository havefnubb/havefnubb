<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Loic Mathaud
* @copyright   2006-2011 Laurent Jouanneau
* @copyright   2007-2008 Loic Mathaud
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseJson extends jResponse{
	public $data=null;
	public function output(){
		global $gJCoord;
		$this->_httpHeaders['Content-Type']="application/json";
		$content=json_encode($this->data);
		$this->_httpHeaders['Content-length']=strlen($content);
		$this->sendHttpHeaders();
		echo $content;
		return true;
	}
	public function outputErrors(){
		global $gJCoord;
		$message=array();
		$message['errorMessage']=$gJCoord->getGenericErrorMessage();
		$e=$gJCoord->getErrorMessage();
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
		$this->_httpHeaders['Content-length']=strlen($content);
		$this->sendHttpHeaders();
		echo $content;
	}
}
