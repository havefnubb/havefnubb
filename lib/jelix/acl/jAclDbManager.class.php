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
class jAclDbManager{
	private function __construct(){}
	public static function addRight($group, $subject, $value , $resource=''){
		$profile = jAclDb::getProfile();
		$daosbj = jDao::get('jelix~jaclsubject', $profile);
		$daorightval = jDao::get('jelix~jaclrightvalues', $profile);
		$sbj = $daosbj->get($subject);
		if(!$sbj) return false;
		$vallist = $daorightval->findByValGroup($sbj->id_aclvalgrp);
		if($resource === null) $resource='';
		$ok=false;
		foreach($vallist as $valueok){
			if($valueok->value == $value){
				$ok = true;
				break;
			}
		}
		if(!$ok) return false;
		$daoright = jDao::get('jelix~jaclrights', $profile);
		$right = $daoright->get($subject,$group,$resource,$value);
		if(!$right){
			$right = jDao::createRecord('jelix~jaclrights', $profile);
			$right->id_aclsbj = $subject;
			$right->id_aclgrp = $group;
			$right->id_aclres = $resource;
			$right->value = $value;
			$daoright->insert($right);
		}
		jAcl::clearCache();
		return true;
	}
	public static function removeRight($group, $subject, $value , $resource=''){
		$daoright = jDao::get('jelix~jaclrights', jAclDb::getProfile());
		if($resource === null) $resource='';
		$daoright->delete($subject,$group,$resource,$value);
		jAcl::clearCache();
	}
	public static function removeResourceRight($subject, $resource){
		$daoright = jDao::get('jelix~jaclrights', jAclDb::getProfile());
		$daoright->deleteBySubjRes($subject, $resource);
		jAcl::clearCache();
	}
	public static function addSubject($subject, $id_aclvalgrp, $label_key){
		$p = jAclDb::getProfile();
		$daosbj = jDao::get('jelix~jaclsubject',$p);
		$subj = jDao::createRecord('jelix~jaclsubject',$p);
		$subj->id_aclsbj=$subject;
		$subj->id_aclvalgrp=$id_aclvalgrp;
		$subj->label_key =$label_key;
		$daosbj->insert($subj);
		jAcl::clearCache();
	}
	public static function removeSubject($subject){
		$p = jAclDb::getProfile();
		$daoright = jDao::get('jelix~jaclrights',$p);
		$daoright->deleteBySubject($subject);
		$daosbj = jDao::get('jelix~jaclsubject',$p);
		$daosbj->delete($subject);
		jAcl::clearCache();
	}
}