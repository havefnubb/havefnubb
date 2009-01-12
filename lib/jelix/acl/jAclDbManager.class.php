<?php
/**
* @package     jelix
* @subpackage  acl
* @author      Laurent Jouanneau
* @copyright   2006-2007 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
* @since 1.0a3
*/


/**
 * This class is used to manage rights. Works only with db driver of jAcl.
 * @package     jelix
 * @subpackage  acl
 * @static
 */
class jAclDbManager {

    /**
     * @internal The constructor is private, because all methods are static
     */
    private function __construct (){ }

    /**
     * add a right on the given subject/group/resource
     * @param int    $group the group id.
     * @param string $subject the key of the subject
     * @param string  $value the value of the right
     * @param string $resource the id of a resource
     * @return boolean  true if the right is set
     */
    public static function addRight($group, $subject, $value , $resource=''){
        $profile = jAclDb::getProfile();
        $daosbj = jDao::get('jelix~jaclsubject', $profile);
        $daorightval = jDao::get('jelix~jaclrightvalues', $profile);

        $sbj = $daosbj->get($subject);
        if(!$sbj) return false;

        //  récupère la liste des valeurs du groupe de valeur
        $vallist = $daorightval->findByValGroup($sbj->id_aclvalgrp);

        if($resource === null) $resource='';

        // on verifie que la valeur est autorisée
        $ok=false;
        foreach($vallist as $valueok){
            if($valueok->value == $value){
                $ok = true;
                break;
            }
        }
        if(!$ok) return false;

        //  ajoute la nouvelle valeur
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

    /**
     * remove a right on the given subject/group/resource
     * @param int    $group the group id.
     * @param string $subject the key of the subject
     * @param string  $value the value of the right
     * @param string $resource the id of a resource
     */
    public static function removeRight($group, $subject, $value , $resource=''){
        $daoright = jDao::get('jelix~jaclrights', jAclDb::getProfile());
        if($resource === null) $resource='';
        $daoright->delete($subject,$group,$resource,$value);
        jAcl::clearCache();
    }



    /**
     * remove the right on the given subject/resource, for all groups
     * @param string  $subject the key of the subject
     * @param string $resource the id of a resource
     */
    public static function removeResourceRight($subject, $resource){
        $daoright = jDao::get('jelix~jaclrights', jAclDb::getProfile());
        $daoright->deleteBySubjRes($subject, $resource);
        jAcl::clearCache();
    }

    /**
     * create a new subject
     * @param string  $subject the key of the subject
     * @param int $id_aclvalgrp the id of the values group with which the right can be set
     * @param string $label_key the key of a locale which represents the label of the subject
     */
    public static function addSubject($subject, $id_aclvalgrp, $label_key){
        // ajoute un sujet dans la table jacl_subject
        $p = jAclDb::getProfile();
        $daosbj = jDao::get('jelix~jaclsubject',$p);
        $subj = jDao::createRecord('jelix~jaclsubject',$p);
        $subj->id_aclsbj=$subject;
        $subj->id_aclvalgrp=$id_aclvalgrp;
        $subj->label_key =$label_key;
        $daosbj->insert($subj);
        jAcl::clearCache();
    }

    /**
     * Delete the given subject
     * @param string  $subject the key of the subject
     */
    public static function removeSubject($subject){
        // supprime dans jacl_rights
        // supprime dans jacl_subject
        $p = jAclDb::getProfile();
        $daoright = jDao::get('jelix~jaclrights',$p);
        $daoright->deleteBySubject($subject);
        $daosbj = jDao::get('jelix~jaclsubject',$p);
        $daosbj->delete($subject);
        jAcl::clearCache();
    }
}

