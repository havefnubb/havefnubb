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
class jCssRequest extends jRequest{
	public $type='css';
	public $defaultResponseType='css';
	public $authorizedResponseClass='jResponseCss';
	protected function _initParams(){
		$url=jUrl::getEngine()->parseFromRequest($this,$_GET);
		$this->params=array_merge($url->params,$_POST);
	}
}
