<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl_driver
* @author      Laurent Jouanneau
* @copyright   2006-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class dbAclDriver implements jIAclDriver{
	function __construct(){}
	protected static $aclres=array();
	protected static $acl=array();
	public function getRight($subject,$resource=null){
		if($resource===null&&isset(self::$acl[$subject])){
			return self::$acl[$subject];
		}elseif(isset(self::$aclres[$subject][$resource])){
			return self::$aclres[$subject][$resource];
		}
		if(!jAuth::isConnected())
			return array();
		$groups=jAclDbUserGroup::getGroups();
		if(count($groups)==0){
			self::$acl[$subject]=array();
			self::$aclres[$subject][$resource]=array();
			return array();
		}
		$values=array();
		$dao=jDao::get('jacldb~jaclrights','jacl_profile');
		$list=$dao->getAllGroupRights($subject,$groups);
		foreach($list as $right){
			$values []=$right->value;
		}
		self::$acl[$subject]=$values;
		if($resource!==null){
			$list=$dao->getAllGroupRightsWithRes($subject,$groups,$resource);
			foreach($list as $right){
				$values []=$right->value;
			}
			self::$aclres[$subject][$resource]=$values=array_unique($values);
		}
		return $values;
	}
	public function clearCache(){
		self::$acl=array();
		self::$aclres=array();
	}
}
