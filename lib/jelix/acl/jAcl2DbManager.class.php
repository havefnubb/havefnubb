<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2011 Laurent Jouanneau
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
			$right->canceled=0;
			$daoright->insert($right);
		}
		else if($right->canceled){
			$right->canceled=false;
			$daoright->update($right);
		}
		jAcl2::clearCache();
		return true;
	}
	public static function removeRight($group,$subject,$resource='',$canceled=false){
		if($resource===null)$resource='';
		$daoright=jDao::get('jacl2db~jacl2rights','jacl2_profile');
		if($canceled){
			$right=$daoright->get($subject,$group,$resource);
			if(!$right){
				$right=jDao::createRecord('jacl2db~jacl2rights','jacl2_profile');
				$right->id_aclsbj=$subject;
				$right->id_aclgrp=$group;
				$right->id_aclres=$resource;
				$right->canceled=$canceled;
				$daoright->insert($right);
			}
			else if($right->canceled!=$canceled){
				$right->canceled=$canceled;
				$daoright->update($right);
			}
		}
		else{
			$daoright->delete($subject,$group,$resource);
		}
		jAcl2::clearCache();
	}
	public static function setRightsOnGroup($group,$rights){
		$dao=jDao::get('jacl2db~jacl2rights','jacl2_profile');
		$oldrights=array();
		$rs=$dao->getRightsByGroup($group);
		foreach($rs as $rec){
			$oldrights [$rec->id_aclsbj]=($rec->canceled?'n':'y');
		}
		foreach($rights as $sbj=>$val){
			if($val===''||$val==false){
			}
			else if($val===true||$val=='y'){
				self::addRight($group,$sbj);
				unset($oldrights[$sbj]);
			}
			else if($val=='n'){
				if(isset($oldrights[$sbj]))
					unset($oldrights[$sbj]);
				self::removeRight($group,$sbj,'',true);
			}
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
	public static function addSubject($subject,$label_key,$subjectGroup=null){
		$subj=jDao::createRecord('jacl2db~jacl2subject','jacl2_profile');
		$subj->id_aclsbj=$subject;
		$subj->label_key=$label_key;
		$subj->id_aclsbjgrp=$subjectGroup;
		jDao::get('jacl2db~jacl2subject','jacl2_profile')->insert($subj);
		jAcl2::clearCache();
	}
	public static function removeSubject($subject){
		jDao::get('jacl2db~jacl2rights','jacl2_profile')->deleteBySubject($subject);
		jDao::get('jacl2db~jacl2subject','jacl2_profile')->delete($subject);
		jAcl2::clearCache();
	}
	public static function addSubjectGroup($subjectGroup,$label_key){
		$subj=jDao::createRecord('jacl2db~jacl2subjectgroup','jacl2_profile');
		$subj->id_aclsbjgrp=$subjectGroup;
		$subj->label_key=$label_key;
		jDao::get('jacl2db~jacl2subjectgroup','jacl2_profile')->insert($subj);
		jAcl2::clearCache();
	}
	public static function removeSubjectGroup($subjectGroup){
		jDao::get('jacl2db~jacl2subject','jacl2_profile')->removeSubjectFromGroup($subjectGroup);
		jDao::get('jacl2db~jacl2subjectgroup','jacl2_profile')->delete($subjectGroup);
		jAcl2::clearCache();
	}
}
