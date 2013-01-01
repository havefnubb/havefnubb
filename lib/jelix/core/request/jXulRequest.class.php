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
class jXulRequest extends jRequest{
	public $type='xul';
	public $defaultResponseType='xul';
	protected function _initParams(){
		$url=jUrl::getEngine()->parseFromRequest($this,$_GET);
		$this->params=array_merge($url->params,$_POST);
	}
	public function getErrorResponse($currentResponse){
		if(isset($_SERVER['HTTP_ACCEPT'])&&strstr($_SERVER['HTTP_ACCEPT'],'text/html')){
			require_once(JELIX_LIB_CORE_PATH.'response/jResponseBasicHtml.class.php');
			$resp=new jResponseBasicHtml();
		}
		elseif($currentResponse){
			$resp=$currentResponse;
		}
		else{
			try{
				$resp=$this->getResponse('',true);
			}
			catch(Exception $e){
				require_once(JELIX_LIB_CORE_PATH.'response/jResponseBasicHtml.class.php');
				$resp=new jResponseBasicHtml();
			}
		}
		return $resp;
	}
	public function isAllowedResponse($response){
		return true;
	}
}
