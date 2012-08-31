<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Sylvain de Vathaire
* @contributor Laurent Jouanneau
* @copyright   2008 Sylvain de Vathaire, 2009-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseSoap extends jResponse{
	protected $_type='soap';
	public $data=null;
	public function output(){
		return true;
	}
	public function outputErrors(){
		global $gJCoord,$gJConfig;
		$e=$gJCoord->getErrorMessage();
		if($e){
			$errorCode=$e->getCode();
			if($errorCode > 5000)
				$errorMessage=$e->getMessage();
			else
				$errorMessage=$gJCoord->getGenericErrorMessage();
		}
		else{
			$errorCode=-1;
			$errorMessage=$gJCoord->getGenericErrorMessage();
		}
		if($gJConfig->charset!='UTF-8'){
			$errorCode=utf8_encode($errorCode);
			$errorMessage=utf8_encode($errorMessage);
		}
		$soapServer=$gJCoord->getSoapServer();
		$soapServer->fault($errorCode,$errorMessage);
	}
}
