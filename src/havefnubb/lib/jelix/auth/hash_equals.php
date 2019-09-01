<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage auth
* @author     Laurent Jouanneau
* @copyright  2015 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
if(!function_exists('hash_equals')){
	function hash_equals($known_str,$user_str){
		if(!is_string($known_str)){
			trigger_error("Expected known_str to be a string, $known_str given",E_USER_WARNING);
			return false;
		}
		if(!is_string($user_str)){
			trigger_error("Expected user_str to be a string, $user_str given",E_USER_WARNING);
			return false;
		}
		$known_len=strlen($known_str);
		$user_len=strlen($user_str);
		if($known_len!=$user_len){
			$user_str=$known_str;
			$result=1;
		}
		else{
			$result=0;
		}
		for($j=0;$j < $known_len;$j++){
			$result|=ord($known_str[$j] ^ $user_str[$j]);
		}
		return($result==0);
	}
}
