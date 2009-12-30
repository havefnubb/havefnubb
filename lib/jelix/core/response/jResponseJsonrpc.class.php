<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Loic Mathaud
* @copyright   2005-2007 Laurent Jouanneau
* @copyright   2007 Loic Mathaud
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseJsonRpc extends jResponse{
	protected $_type = 'jsonrpc';
	protected $_acceptSeveralErrors=false;
	public $response = null;
	public function output(){
		global $gJCoord;
		$this->_httpHeaders['Content-Type'] = "application/json";
		if($gJCoord->request->jsonRequestId !== null){
			$content = jJsonRpc::encodeResponse($this->response, $gJCoord->request->jsonRequestId);
			if($this->hasErrors()) return false;
			$this->_httpHeaders['Content-length'] = strlen($content);
			$this->sendHttpHeaders();
			echo $content;
		}else{
			if($this->hasErrors()) return false;
			$this->_httpHeaders['Content-length'] = '0';
			$this->sendHttpHeaders();
		}
		return true;
	}
	public function outputErrors(){
		global $gJCoord;
		if(count($gJCoord->errorMessages)){
			$e = $gJCoord->errorMessages[0];
			$errorCode = $e[1];
			$errorMessage = '['.$e[0].'] '.$e[2].' (file: '.$e[3].', line: '.$e[4].')';
		}else{
			$errorMessage = 'Unknow error';
			$errorCode = -1;
		}
		$this->clearHttpHeaders();
		$this->_httpStatusCode ='500';
		$this->_httpStatusMsg ='Internal Server Error';
		$this->_httpHeaders['Content-Type'] = "application/json";
		$content = jJsonRpc::encodeFaultResponse($errorCode,$errorMessage, $gJCoord->request->jsonRequestId);
		$this->_httpHeaders['Content-length'] = strlen($content);
		$this->sendHttpHeaders();
		echo $content;
	}
}