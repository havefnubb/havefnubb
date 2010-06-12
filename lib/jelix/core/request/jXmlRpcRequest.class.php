<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_request
* @author      Laurent Jouanneau
* @contributor Frederic Guillot
* @contributor Thibault PIRONT < nuKs >
* @copyright   2005-2006 Laurent Jouanneau, 2007 Frederic Guillot
* @copyright   2007 Thibault PIRONT
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require(JELIX_LIB_UTILS_PATH. 'jXmlRpc.class.php');
class jXmlRpcRequest extends jRequest{
	public $type='xmlrpc';
	public $defaultResponseType='xmlrpc';
	protected function _initParams(){
		global $HTTP_RAW_POST_DATA;
		if(isset($HTTP_RAW_POST_DATA)){
			$requestXml=$HTTP_RAW_POST_DATA;
		}else{
			$requestXml=file('php://input');
			$requestXml=implode("\n",$requestXml);
		}
		list($nom,$vars)=jXmlRpc::decodeRequest($requestXml);
		list($module,$action)=explode(':',$nom,2);
		if(count($vars)==1&&is_array($vars[0]))
			$this->params=$vars[0];
		$this->params['params']=$vars;
		$this->params['module']=$module;
		$this->params['action']=$action;
	}
	public function isAllowedResponse($respclass){
		return('jResponseXmlrpc'==$respclass);
	}
}
