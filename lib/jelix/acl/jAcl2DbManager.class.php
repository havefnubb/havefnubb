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
class jAcl2DbManager{
	private function __construct(){}
	public static function addRight($group, $subject, $resource=''){
		$profile = jAcl2Db::getProfile();
		$sbj = jDao::get('jelix~jacl2subject', $profile)->get($subject);
		if(!$sbj) return false;
		if($resource === null) $resource='';
		$daoright = jDao::get('jelix~jacl2rights', $profile);
		$right = $daoright->get($subject,$group,$resource);
		if(!$right){
			$right = jDao::createRecord('jelix~jacl2rights', $profile);
			$right->id_aclsbj = $subject;
			$right->id_aclgrp = $group;
			$right->id_aclres = $resource;
			$daoright->insert($right);
		}
		jAcl2::clearCache();
		return true;
	}
	public static function removeRight($group, $subject, $resource=''){
		if($resource === null) $resource='';
		jDao::get('jelix~jacl2rights', jAcl2Db::getProfile())
			->delete($subject,$group,$resource);
		jAcl2::clearCache();
	}
	public static function setRightsOnGroup($group, $rights){
		$dao = jDao::get('jelix~jacl2rights', jAcl2Db::getProfile());
		$oldrights = array();
		$rs = $dao->getRightsByGroup($group);
		foreach($rs as $rec){
			$oldrights [$rec->id_aclsbj] = true;
		}
		foreach($rights as $sbj=>$val){
			if($val != '' || $val == true){
				if(!isset($oldrights[$sbj]))
					self::addRight($group,$sbj);
				else
					unset($oldrights[$sbj]);
			}
			else
				$oldrights [$sbj] = true;
		}
		if(count($oldrights)){
			$dao->deleteByGroupAndSubjects($group, array_keys($oldrights));
		}
		jAcl2::clearCache();
	}
	public static function removeResourceRight($subject, $resource){
		jDao::get('jelix~jacl2rights', jAcl2Db::getProfile())->deleteBySubjRes($subject, $resource);
		jAcl2::clearCache();
	}
	public static function addSubject($subject, $label_key){
		$p = jAcl2Db::getProfile();
		$subj = jDao::createRecord('jelix~jacl2subject',$p);
		$subj->id_aclsbj=$subject;
		$subj->label_key =$label_key;
		jDao::get('jelix~jacl2subject',$p)->insert($subj);
		jAcl2::clearCache();
	}
	public static function removeSubject($subject){
		$p = jAcl2Db::getProfile();
		jDao::get('jelix~jacl2rights',$p)->deleteBySubject($subject);
		jDao::get('jelix~jacl2subject',$p)->delete($subject);
		jAcl2::clearCache();
	}
}