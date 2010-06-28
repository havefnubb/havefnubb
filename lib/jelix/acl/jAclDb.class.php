<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.0b3
*/
class jAclDb{
	private function __construct(){}
	public static function getProfile(){
		return 'jacl_profile';
	}
	public static function getProfil(){
		trigger_error("jAclDb::getProfil() is deprecated, you should use jAclDb::getProfile()",E_USER_NOTICE);
		return 'jacl_profile';
	}
}
