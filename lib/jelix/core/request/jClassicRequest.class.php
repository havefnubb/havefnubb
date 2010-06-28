<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_request
* @author      Laurent Jouanneau
* @contributor Yoan Blanc
* @copyright   2005-2010 Laurent Jouanneau, 2008 Yoan Blanc
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jClassicRequest extends jRequest{
	public $type='classic';
	public $defaultResponseType='html';
	protected function _initParams(){
		$url=jUrl::parseFromRequest($this,$_GET);
		if($_SERVER['REQUEST_METHOD']=='PUT'){
			$_PUT=$this->readHttpBody();
			if(is_string($_PUT))
				$this->params['__httpbody']=$_PUT;
			else
				$this->params=array_merge($url->params,$_PUT);
		}
		else{
			$this->params=array_merge($url->params,$_POST);
		}
	}
}
