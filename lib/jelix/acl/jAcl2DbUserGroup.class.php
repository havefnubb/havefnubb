<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @copyright   2006-2010 Laurent Jouanneau
* @copyright   2009 Julien Issler
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.1
*/
class jAcl2DbUserGroup{
	private function __construct(){}
	public static function isMemberOfGroup($groupid){
		return in_array($groupid,self::getGroups());
	}
	public static function getGroups(){
		static $groups=null;
		if(!jAuth::isConnected())
			return array();
		if($groups===null){
			$gp=jDao::get('jacl2db~jacl2usergroup','jacl2_profile')
					->getGroupsUser(jAuth::getUserSession()->login);
			$groups=array();
			foreach($gp as $g){
				$groups[]=intval($g->id_aclgrp);
			}
		}
		return $groups;
	}
	public static function getPrivateGroup($login=null){
		if(!$login){
			if(!jAuth::isConnected())
				return null;
			$login=jAuth::getUserSession()->login;
		}
		return jDao::get('jacl2db~jacl2group','jacl2_profile')->getPrivateGroup($login)->id_aclgrp;
	}
	public static function getGroupByCode($code){
		return jDao::get('jacl2db~jacl2group','jacl2_profile')->getGroupByCode($code)->id_aclgrp;
	}
	public static function getUsersList($groupid){
		return jDao::get('jacl2db~jacl2usergroup','jacl2_profile')->getUsersGroup($groupid);
	}
	public static function createUser($login,$defaultGroup=true){
		$daousergroup=jDao::get('jacl2db~jacl2usergroup','jacl2_profile');
		$daogroup=jDao::get('jacl2db~jacl2group','jacl2_profile');
		$usergrp=jDao::createRecord('jacl2db~jacl2usergroup','jacl2_profile');
		$usergrp->login=$login;
		if($defaultGroup){
			$defgrp=$daogroup->getDefaultGroups();
			foreach($defgrp as $group){
				$usergrp->id_aclgrp=$group->id_aclgrp;
				$daousergroup->insert($usergrp);
			}
		}
		$persgrp=jDao::createRecord('jacl2db~jacl2group','jacl2_profile');
		$persgrp->name=$login;
		$persgrp->grouptype=2;
		$persgrp->ownerlogin=$login;
		$daogroup->insert($persgrp);
		$usergrp->id_aclgrp=$persgrp->id_aclgrp;
		$daousergroup->insert($usergrp);
	}
	public static function addUserToGroup($login,$groupid){
		if($groupid==0)
			throw new Exception('jAcl2DbUserGroup::addUserToGroup : invalid group id');
		$usergrp=jDao::createRecord('jacl2db~jacl2usergroup','jacl2_profile');
		$usergrp->login=$login;
		$usergrp->id_aclgrp=$groupid;
		jDao::get('jacl2db~jacl2usergroup','jacl2_profile')->insert($usergrp);
	}
	public static function removeUserFromGroup($login,$groupid){
		jDao::get('jacl2db~jacl2usergroup','jacl2_profile')->delete($login,$groupid);
	}
	public static function removeUser($login){
		$daogroup=jDao::get('jacl2db~jacl2group','jacl2_profile');
		$privategrp=$daogroup->getPrivateGroup($login);
		if(!$privategrp)return;
		jDao::get('jacl2db~jacl2rights','jacl2_profile')->deleteByGroup($privategrp->id_aclgrp);
		jDao::get('jacl2db~jacl2usergroup','jacl2_profile')->deleteByUser($login);
		$daogroup->delete($privategrp->id_aclgrp);
	}
	public static function createGroup($name,$code=null){
		$group=jDao::createRecord('jacl2db~jacl2group','jacl2_profile');
		$group->name=$name;
		$group->code=$code;
		$group->grouptype=0;
		jDao::get('jacl2db~jacl2group','jacl2_profile')->insert($group);
		return $group->id_aclgrp;
	}
	public static function setDefaultGroup($groupid,$default=true){
		if($groupid==0)
			throw new Exception('jAcl2DbUserGroup::setDefaultGroup : invalid group id');
		$daogroup=jDao::get('jacl2db~jacl2group','jacl2_profile');
		if($default)
			$daogroup->setToDefault($groupid);
		else
			$daogroup->setToNormal($groupid);
	}
	public static function updateGroup($groupid,$name){
		if($groupid==0)
			throw new Exception('jAcl2DbUserGroup::updateGroup : invalid group id');
		jDao::get('jacl2db~jacl2group','jacl2_profile')->changeName($groupid,$name);
	}
	public static function removeGroup($groupid){
		if($groupid==0)
			throw new Exception('jAcl2DbUserGroup::removeGroup : invalid group id');
		jDao::get('jacl2db~jacl2rights','jacl2_profile')->deleteByGroup($groupid);
		jDao::get('jacl2db~jacl2usergroup','jacl2_profile')->deleteByGroup($groupid);
		jDao::get('jacl2db~jacl2group','jacl2_profile')->delete($groupid);
	}
	public static function getGroupList($login=''){
		if($login===''){
			return jDao::get('jacl2db~jacl2group','jacl2_profile')->findAllPublicGroup();
		}else{
			return jDao::get('jacl2db~jacl2groupsofuser','jacl2_profile')->getGroupsUser($login);
		}
	}
}
