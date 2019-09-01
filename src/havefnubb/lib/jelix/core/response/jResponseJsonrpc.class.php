<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Loic Mathaud, Julien Issler
* @copyright   2005-2010 Laurent Jouanneau
* @copyright   2007 Loic Mathaud
* @copyright   2017 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseJsonRpc extends jResponse{
	protected $_type='jsonrpc';
	public $response=null;
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']="application/json";
		$req=jApp::coord()->request;
		if($req->jsonRequestId!==null){
			$content=jJsonRpc::encodeResponse($this->response,$req->jsonRequestId);
			$this->sendHttpHeaders();
			echo $content;
		}
		else{
			$this->_httpHeaders['Content-length']='0';
			$this->sendHttpHeaders();
		}
		return true;
	}
	public function outputErrors(){
		$coord=jApp::coord();
		$e=$coord->getErrorMessage();
		if($e){
			$errorCode=$e->getCode();
			if($errorCode > 5000)
				$errorMessage=$e->getMessage();
			else
				$errorMessage=$coord->getGenericErrorMessage();
		}
		else{
			$errorCode=-1;
			$errorMessage=$coord->getGenericErrorMessage();
		}
		$this->clearHttpHeaders();
		$this->_httpStatusCode='500';
		$this->_httpStatusMsg='Internal Server Error';
		$this->_httpHeaders['Content-Type']="application/json";
		$content=jJsonRpc::encodeFaultResponse($errorCode,$errorMessage,$coord->request->jsonRequestId);
		$this->sendHttpHeaders();
		echo $content;
	}
}
