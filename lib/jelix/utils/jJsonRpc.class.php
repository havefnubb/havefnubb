<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor Julien ISSLER
* @copyright   2005-2007 Laurent Jouanneau
* @copyright   2007 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jJsonRpc{
	private function __construct(){}
	public static function decodeRequest($content){
		$obj = json_decode($content,true);
		return $obj;
	}
	public static function encodeRequest($methodname, $params, $id=1){
		return '{"method":"'.$methodname.'","params":'.json_encode($params).',"id":'.json_encode($id).'}';
	}
	public static function decodeResponse($content){
		return json_decode($content,true);
	}
	public static function encodeResponse($params, $id=1){
		return '{"result":'.json_encode($params).',"error":null,"id":'.json_encode($id).'}';
	}
	public static function encodeFaultResponse($code, $message, $id=1){
		return '{"result":null,"error":{"code": '.json_encode($code).', "string":'.json_encode($message).' },"id":'.json_encode($id).'}';
	}
}