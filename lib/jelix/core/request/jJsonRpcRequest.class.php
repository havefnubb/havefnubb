<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_request
* @author      Laurent Jouanneau
* @copyright   2005-2011 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require(JELIX_LIB_UTILS_PATH.'jJsonRpc.class.php');
class jJsonRpcRequest extends jRequest{
	public $type='jsonrpc';
	public $defaultResponseType='jsonrpc';
	public $authorizedResponseClass='jResponseJsonrpc';
	public $jsonRequestId=null;
	protected function _initParams(){
		global $HTTP_RAW_POST_DATA;
		if(isset($HTTP_RAW_POST_DATA)){
			$request=$HTTP_RAW_POST_DATA;
		}else{
			$request=file('php://input');
			$request=implode("\n",$request);
		}
		$requestobj=jJsonRpc::decodeRequest($request);
		if($requestobj['method']){
			list($module,$action)=explode('~',$requestobj['method']);
		}else{
			$module='';
			$action='';
		}
		if(isset($requestobj['id']))
			$this->jsonRequestId=$requestobj['id'];
		if(is_array($requestobj['params']))
			$this->params=$requestobj['params'];
		$this->params['params']=$requestobj['params'];
		$this->params['module']=$module;
		$this->params['action']=$action;
	}
}
