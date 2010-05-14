<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Sylvain de Vathaire
* @contributor 
* @copyright   2008 Sylvain de Vathaire
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseSoap extends jResponse{
	protected $_type = 'soap';
	protected $_acceptSeveralErrors=false;
	public $data = null;
	public function output(){
		if($this->hasErrors()) return false;
		return true;
	}
	public function outputErrors(){
		global $gJCoord, $gJConfig;
	   if(count($gJCoord->errorMessages)){
			$e = $gJCoord->errorMessages[0];
			$errorCode = $e[1];
			$errorMessage = '['.$e[0].'] '.$e[2].' (file: '.$e[3].', line: '.$e[4].')';
		}else{
			$errorMessage = 'Unknown error';
			$errorCode = -1;
		}
		if($gJConfig->charset != 'UTF-8'){
			$errorCode  = utf8_encode($errorCode);
			$errorMessage = utf8_encode($errorMessage);
		}
		$soapServer = $gJCoord->getSoapServer();
		$soapServer->fault($errorCode, $errorMessage);
	}
}