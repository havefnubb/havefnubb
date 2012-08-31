<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   core
* @author       Sylvain de Vathaire
* @contributor  Laurent Jouanneau
* @copyright    2008 Sylvain DE VATHAIRE, 2008 Laurent Jouanneau
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_UTILS_PATH.'jWSDL.class.php');
class jSoapCoordinator extends jCoordinator{
	public $wsdl;
	protected $soapServer;
	public function processSoap(){
		global $gJConfig;
		$this->wsdl=new jWSDL($this->request->params['module'],$this->request->params['action']);
		$this->soapServer=$this->getSoapServer($this->wsdl);
		$this->soapServer->setclass('jSoapHandler',$this);
		$this->soapServer->handle($this->request->soapMsg);
	}
	public function getSoapServer($wsdl=null){
		global $gJConfig;
		if(is_null($this->soapServer)){
			if(is_null($wsdl)){
				$this->soapServer=new SoapServer(null,array('soap_version'=>SOAP_1_1,'encoding'=>$gJConfig->charset,'uri'=>$_SERVER['PHP_SELF']));
			}else{
				$this->soapServer=new SoapServer($wsdl->getWSDLFilePath(),array('soap_version'=>SOAP_1_1,'encoding'=>$gJConfig->charset));
			}
		}
		return $this->soapServer;
	}
}
class jSoapHandler{
	protected $coord;
	function __construct($coordinator){
		$this->coord=$coordinator;
	}
	function __call($soapOperationName,$soapArgs){
		$this->coord->request->params['action'].=':'.$soapOperationName;
		$operationParams=$this->coord->wsdl->getOperationParams($soapOperationName);
		foreach(array_keys($operationParams)as $i=>$paramName){
			$this->coord->request->params[$paramName]=$soapArgs[$i];
		}
		$this->coord->process($this->coord->request);
		$response=$this->coord->response;
		if(($c=get_class($response))=='jResponseRedirect'
				||$c=='jResponseRedirectUrl')
			return null;
		return $this->coord->response->data;
	}
}
