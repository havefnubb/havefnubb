<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.1
*/
class jAcl2Db{
	private function __construct(){}
	public static function getProfile(){
		static $profile='';
		if($profile== ''){
			try{
				$prof = jDb::getProfile('jacl_profile', true);
			}catch(Exception $e){
				$prof = jDb::getProfile();
			}
			$profile = $prof['name'];
		}
		return $profile;
	}
}