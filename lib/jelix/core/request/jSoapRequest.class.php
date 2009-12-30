<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_request
* @author      Sylvain de Vathaire
* @contributor
* @copyright   2008 Sylvain de Vathaire
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jSoapRequest extends jRequest{
	public $type = 'soap';
	public $defaultResponseType = 'soap';
	public $soapMsg;
	function __construct(){}
	function initService(){
	   if(isset($_GET["service"]) && $_GET['service'] != ''){
			list($module, $action) =  explode('~',$_GET["service"]);
		}else{
			throw new JException('jWSDL~errors.service.param.required');
		}
		$this->params['module'] = $module;
		$this->params['action'] = $action;
		if(isset($HTTP_RAW_POST_DATA) &&($HTTP_RAW_POST_DATA!='')){
			$this->soapMsg = $HTTP_RAW_POST_DATA;
		}else{
			$this->soapMsg = file("php://input");
			$this->soapMsg = implode(" ", $this->soapMsg);
		}
		$this->_initUrlData();
	}
	function init(){}
	protected function _initParams(){}
	public function isAllowedResponse($respclass){
		return('jResponseSoap' == $respclass);
	}
}