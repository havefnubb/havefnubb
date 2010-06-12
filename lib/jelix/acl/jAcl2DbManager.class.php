<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.1
*/
class jAcl2DbManager{
	private function __construct(){}
	public static function addRight($group,$subject,$resource=''){
		$sbj=jDao::get('jacl2db~jacl2subject','jacl2_profile')->get($subject);
		if(!$sbj)return false;
		if($resource===null)$resource='';
		$daoright=jDao::get('jacl2db~jacl2rights','jacl2_profile');
		$right=$daoright->get($subject,$group,$resource);
		if(!$right){
			$right=jDao::createRecord('jacl2db~jacl2rights','jacl2_profile');
			$right->id_aclsbj=$subject;
			$right->id_aclgrp=$group;
			$right->id_aclres=$resource;
			$daoright->insert($right);
		}
		jAcl2::clearCache();
		return true;
	}
	public static function removeRight($group,$subject,$resource=''){
		if($resource===null)$resource='';
		jDao::get('jacl2db~jacl2rights','jacl2_profile')
			->delete($subject,$group,$resource);
		jAcl2::clearCache();
	}
	public static function setRightsOnGroup($group,$rights){
		$dao=jDao::get('jacl2db~jacl2rights','jacl2_profile');
		$oldrights=array();
		$rs=$dao->getRightsByGroup($group);
		foreach($rs as $rec){
			$oldrights [$rec->id_aclsbj]=true;
		}
		foreach($rights as $sbj=>$val){
			if($val!=''||$val==true){
				if(!isset($oldrights[$sbj]))
					self::addRight($group,$sbj);
				else
				unset($oldrights[$sbj]);
			}
			else
				$oldrights [$sbj]=true;
		}
		if(count($oldrights)){
			$dao->deleteByGroupAndSubjects($group,array_keys($oldrights));
		}
		jAcl2::clearCache();
	}
	public static function removeResourceRight($subject,$resource){
		jDao::get('jacl2db~jacl2rights','jacl2_profile')->deleteBySubjRes($subject,$resource);
		jAcl2::clearCache();
	}
	public static function addSubject($subject,$label_key){
		$subj=jDao::createRecord('jacl2db~jacl2subject','jacl2_profile');
		$subj->id_aclsbj=$subject;
		$subj->label_key=$label_key;
		jDao::get('jacl2db~jacl2subject','jacl2_profile')->insert($subj);
		jAcl2::clearCache();
	}
	public static function removeSubject($subject){
		jDao::get('jacl2db~jacl2rights','jacl2_profile')->deleteBySubject($subject);
		jDao::get('jacl2db~jacl2subject','jacl2_profile')->delete($subject);
		jAcl2::clearCache();
	}
}
