<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.0a3
*/
class jAclDbUserGroup{
	private function __construct(){}
	public static function isMemberOfGroup($groupid){
		$groups=self::getGroups();
		return in_array($groupid,$groups);
	}
	public static function getGroups(){
		static $groups=null;
		if(!jAuth::isConnected())
			return array();
		if($groups===null){
			$dao=jDao::get('jacldb~jaclusergroup','jacl_profile');
			$gp=$dao->getGroupsUser(jAuth::getUserSession()->login);
			$groups=array();
			foreach($gp as $g){
				$groups[]=intval($g->id_aclgrp);
			}
		}
		return $groups;
	}
	public static function getUsersList($groupid){
		$dao=jDao::get('jacldb~jaclusergroup','jacl_profile');
		return $dao->getUsersGroup($groupid);
	}
	public static function createUser($login,$defaultGroup=true){
		$daousergroup=jDao::get('jacldb~jaclusergroup','jacl_profile');
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		$usergrp=jDao::createRecord('jacldb~jaclusergroup','jacl_profile');
		$usergrp->login=$login;
		if($defaultGroup){
			$defgrp=$daogroup->getDefaultGroups();
			foreach($defgrp as $group){
				$usergrp->id_aclgrp=$group->id_aclgrp;
				$daousergroup->insert($usergrp);
			}
		}
		$persgrp=jDao::createRecord('jacldb~jaclgroup','jacl_profile');
		$persgrp->name=$login;
		$persgrp->grouptype=2;
		$persgrp->ownerlogin=$login;
		$daogroup->insert($persgrp);
		$usergrp->id_aclgrp=$persgrp->id_aclgrp;
		$daousergroup->insert($usergrp);
	}
	public static function addUserToGroup($login,$groupid){
		$daousergroup=jDao::get('jacldb~jaclusergroup','jacl_profile');
		$usergrp=jDao::createRecord('jacldb~jaclusergroup','jacl_profile');
		$usergrp->login=$login;
		$usergrp->id_aclgrp=$groupid;
		$daousergroup->insert($usergrp);
	}
	public static function removeUserFromGroup($login,$groupid){
		$daousergroup=jDao::get('jacldb~jaclusergroup','jacl_profile');
		$daousergroup->delete($login,$groupid);
	}
	public static function removeUser($login){
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		$daoright=jDao::get('jacldb~jaclrights','jacl_profile');
		$daousergroup=jDao::get('jacldb~jaclusergroup','jacl_profile');
		$privategrp=$daogroup->getPrivateGroup($login);
		if(!$privategrp)return;
		$daoright->deleteByGroup($privategrp->id_aclgrp);
		$daogroup->delete($privategrp->id_aclgrp);
		$daousergroup->deleteByUser($login);
	}
	public static function createGroup($name){
		$group=jDao::createRecord('jacldb~jaclgroup','jacl_profile');
		$group->name=$name;
		$group->grouptype=0;
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		$daogroup->insert($group);
		return $group->id_aclgrp;
	}
	public static function setDefaultGroup($groupid,$default=true){
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		if($default)
			$daogroup->setToDefault($groupid);
		else
			$daogroup->setToNormal($groupid);
	}
	public static function updateGroup($groupid,$name){
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		$daogroup->changeName($groupid,$name);
	}
	public static function removeGroup($groupid){
		$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
		$daoright=jDao::get('jacldb~jaclrights','jacl_profile');
		$daousergroup=jDao::get('jacldb~jaclusergroup','jacl_profile');
		$daoright->deleteByGroup($groupid);
		$daousergroup->deleteByGroup($groupid);
		$daogroup->delete($groupid);
	}
	public static function getGroupList($login=''){
		if($login===''){
			$daogroup=jDao::get('jacldb~jaclgroup','jacl_profile');
			return $daogroup->findAllPublicGroup();
		}else{
			$daogroup=jDao::get('jacldb~jaclgroupsofuser','jacl_profile');
			return $daogroup->getGroupsUser($login);
		}
	}
}
