<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.0a3
*/
class jAclDbUserGroup{
	private function __construct(){}
	public static function isMemberOfGroup($groupid){
		$groups = self::getGroups();
		return in_array($groupid, $groups);
	}
	public static function getGroups(){
		static $groups = null;
		if(!jAuth::isConnected())
			return array();
		if($groups === null){
			$dao = jDao::get('jelix~jaclusergroup', jAclDb::getProfile());
			$gp = $dao->getGroupsUser(jAuth::getUserSession()->login);
			$groups = array();
			foreach($gp as $g){
				$groups[]=intval($g->id_aclgrp);
			}
		}
		return $groups;
	}
	public static function getUsersList($groupid){
		$dao = jDao::get('jelix~jaclusergroup', jAclDb::getProfile());
		return $dao->getUsersGroup($groupid);
	}
	public static function createUser($login, $defaultGroup=true){
		$p = jAclDb::getProfile();
		$daousergroup = jDao::get('jelix~jaclusergroup',$p);
		$daogroup = jDao::get('jelix~jaclgroup',$p);
		$usergrp = jDao::createRecord('jelix~jaclusergroup',$p);
		$usergrp->login =$login;
		if($defaultGroup){
			$defgrp = $daogroup->getDefaultGroups();
			foreach($defgrp as $group){
				$usergrp->id_aclgrp = $group->id_aclgrp;
				$daousergroup->insert($usergrp);
			}
		}
		$persgrp = jDao::createRecord('jelix~jaclgroup',$p);
		$persgrp->name = $login;
		$persgrp->grouptype = 2;
		$persgrp->ownerlogin = $login;
		$daogroup->insert($persgrp);
		$usergrp->id_aclgrp = $persgrp->id_aclgrp;
		$daousergroup->insert($usergrp);
	}
	public static function addUserToGroup($login, $groupid){
		$p = jAclDb::getProfile();
		$daousergroup = jDao::get('jelix~jaclusergroup',$p);
		$usergrp = jDao::createRecord('jelix~jaclusergroup',$p);
		$usergrp->login =$login;
		$usergrp->id_aclgrp = $groupid;
		$daousergroup->insert($usergrp);
	}
	public static function removeUserFromGroup($login,$groupid){
		$daousergroup = jDao::get('jelix~jaclusergroup',jAclDb::getProfile());
		$daousergroup->delete($login,$groupid);
	}
	public static function removeUser($login){
		$p = jAclDb::getProfile();
		$daogroup = jDao::get('jelix~jaclgroup',$p);
		$daoright = jDao::get('jelix~jaclrights',$p);
		$daousergroup = jDao::get('jelix~jaclusergroup',$p);
		$privategrp = $daogroup->getPrivateGroup($login);
		if(!$privategrp) return;
		$daoright->deleteByGroup($privategrp->id_aclgrp);
		$daogroup->delete($privategrp->id_aclgrp);
		$daousergroup->deleteByUser($login);
	}
	public static function createGroup($name){
		$p = jAclDb::getProfile();
		$group = jDao::createRecord('jelix~jaclgroup',$p);
		$group->name=$name;
		$group->grouptype=0;
		$daogroup = jDao::get('jelix~jaclgroup',$p);
		$daogroup->insert($group);
		return $group->id_aclgrp;
	}
	public static function setDefaultGroup($groupid, $default=true){
		$daogroup = jDao::get('jelix~jaclgroup',jAclDb::getProfile());
		if($default)
			$daogroup->setToDefault($groupid);
		else
			$daogroup->setToNormal($groupid);
	}
	public static function updateGroup($groupid, $name){
		$daogroup = jDao::get('jelix~jaclgroup',jAclDb::getProfile());
		$daogroup->changeName($groupid,$name);
	}
	public static function removeGroup($groupid){
		$p = jAclDb::getProfile();
		$daogroup = jDao::get('jelix~jaclgroup',$p);
		$daoright = jDao::get('jelix~jaclrights',$p);
		$daousergroup = jDao::get('jelix~jaclusergroup',$p);
		$daoright->deleteByGroup($groupid);
		$daousergroup->deleteByGroup($groupid);
		$daogroup->delete($groupid);
	}
	public static function getGroupList($login=''){
		if($login === ''){
			$daogroup = jDao::get('jelix~jaclgroup',jAclDb::getProfile());
			return $daogroup->findAllPublicGroup();
		}else{
			$daogroup = jDao::get('jelix~jaclgroupsofuser',jAclDb::getProfile());
			return $daogroup->getGroupsUser($login);
		}
	}
}