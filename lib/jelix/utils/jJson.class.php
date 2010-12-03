<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @copyright   2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
define('SERVICES_JSON_STRICT_TYPE',0);
define('SERVICES_JSON_LOOSE_TYPE',16);
class jJson{
	private $use;
	function jJSON($use=0)
	{
		$this->use=$use;
	}
	function encode($var){
		return json_encode($var);
	}
	function decode($str){
		return json_decode($str,($this->use==SERVICES_JSON_LOOSE_TYPE));
	}
}
