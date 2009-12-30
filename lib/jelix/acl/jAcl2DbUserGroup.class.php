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
class jAcl2DbUserGroup{
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
			$gp = jDao::get('jelix~jacl2usergroup', jAcl2Db::getProfile())
					->getGroupsUser(jAuth::getUserSession()->login);
			$groups = array();
			foreach($gp as $g){
				$groups[]=intval($g->id_aclgrp);
			}
		}
		return $groups;
	}
	public static function getUsersList($groupid){
		return jDao::get('jelix~jacl2usergroup', jAcl2Db::getProfile())->getUsersGroup($groupid);
	}
	public static function createUser($login, $defaultGroup=true){
		$p = jAcl2Db::getProfile();
		$daousergroup = jDao::get('jelix~jacl2usergroup',$p);
		$daogroup = jDao::get('jelix~jacl2group',$p);
		$usergrp = jDao::createRecord('jelix~jacl2usergroup',$p);
		$usergrp->login =$login;
		if($defaultGroup){
			$defgrp = $daogroup->getDefaultGroups();
			foreach($defgrp as $group){
				$usergrp->id_aclgrp = $group->id_aclgrp;
				$daousergroup->insert($usergrp);
			}
		}
		$persgrp = jDao::createRecord('jelix~jacl2group',$p);
		$persgrp->name = $login;
		$persgrp->grouptype = 2;
		$persgrp->ownerlogin = $login;
		$daogroup->insert($persgrp);
		$usergrp->id_aclgrp = $persgrp->id_aclgrp;
		$daousergroup->insert($usergrp);
	}
	public static function addUserToGroup($login, $groupid){
		if( $groupid == 0)
			throw new Exception('jAcl2DbUserGroup::addUserToGroup : invalid group id');
		$p = jAcl2Db::getProfile();
		$usergrp = jDao::createRecord('jelix~jacl2usergroup',$p);
		$usergrp->login =$login;
		$usergrp->id_aclgrp = $groupid;
		jDao::get('jelix~jacl2usergroup',$p)->insert($usergrp);
	}
	public static function removeUserFromGroup($login,$groupid){
		jDao::get('jelix~jacl2usergroup',jAcl2Db::getProfile())->delete($login,$groupid);
	}
	public static function removeUser($login){
		$p = jAcl2Db::getProfile();
		$daogroup = jDao::get('jelix~jacl2group',$p);
		$privategrp = $daogroup->getPrivateGroup($login);
		if(!$privategrp) return;
		jDao::get('jelix~jacl2rights',$p)->deleteByGroup($privategrp->id_aclgrp);
		$daogroup->delete($privategrp->id_aclgrp);
		jDao::get('jelix~jacl2usergroup',$p)->deleteByUser($login);
	}
	public static function createGroup($name){
		$p = jAcl2Db::getProfile();
		$group = jDao::createRecord('jelix~jacl2group',$p);
		$group->name=$name;
		$group->grouptype=0;
		jDao::get('jelix~jacl2group',$p)->insert($group);
		return $group->id_aclgrp;
	}
	public static function setDefaultGroup($groupid, $default=true){
		if( $groupid == 0)
			throw new Exception('jAcl2DbUserGroup::setDefaultGroup : invalid group id');
		$daogroup = jDao::get('jelix~jacl2group',jAcl2Db::getProfile());
		if($default)
			$daogroup->setToDefault($groupid);
		else
			$daogroup->setToNormal($groupid);
	}
	public static function updateGroup($groupid, $name){
		if( $groupid == 0)
			throw new Exception('jAcl2DbUserGroup::updateGroup : invalid group id');
		jDao::get('jelix~jacl2group',jAcl2Db::getProfile())->changeName($groupid,$name);
	}
	public static function removeGroup($groupid){
		if( $groupid == 0)
			throw new Exception('jAcl2DbUserGroup::removeGroup : invalid group id');
		$p = jAcl2Db::getProfile();
		jDao::get('jelix~jacl2rights',$p)->deleteByGroup($groupid);
		jDao::get('jelix~jacl2usergroup',$p)->deleteByGroup($groupid);
		jDao::get('jelix~jacl2group',$p)->delete($groupid);
	}
	public static function getGroupList($login=''){
		if($login === ''){
			return jDao::get('jelix~jacl2group',jAcl2Db::getProfile())->findAllPublicGroup();
		}else{
			return jDao::get('jelix~jacl2groupsofuser',jAcl2Db::getProfile())->getGroupsUser($login);
		}
	}
}