<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * class that manage the rights of the forum
*/
class hfnuadminrights {

    /**
     * set the defaults rights
     * var $__defaultRights array
     */
    private  static $__defaultRights = array(
        //anonymous
        '__anonymous'=>array( 'hfnu.forum.list'=>'on',
                    'hfnu.forum.view'=>'on',
                    'hfnu.posts.list'=>'on',
                    'hfnu.posts.view'=>'on',
                    'hfnu.posts.rss'=>'on'
                    ),
        //admins
        'admins'=>array( 'hfnu.forum.list'=>'on',
                    'hfnu.forum.view'=>'on',
                    'hfnu.posts.create'=>'on',
                    'hfnu.posts.delete'=>'on',
                    'hfnu.posts.edit'=>'on',
                    'hfnu.posts.edit.own'=>'on',
                    'hfnu.posts.list'=>'on',
                    'hfnu.posts.notify'=>'on',
                    'hfnu.posts.reply'=>'on',
                    'hfnu.posts.quote'=>'on',
                    'hfnu.posts.view'=>'on',
                    'hfnu.posts.rss'=>'on'
                    ),
        //moderators
        'moderators'=>array( 'hfnu.forum.list'=>'on',
                    'hfnu.forum.view'=>'on',
                    'hfnu.posts.create'=>'on',
                    'hfnu.posts.edit'=>'on',
                    'hfnu.posts.edit.own'=>'on',
                    'hfnu.posts.list'=>'on',
                    'hfnu.posts.notify'=>'on',
                    'hfnu.posts.reply'=>'on',
                    'hfnu.posts.quote'=>'on',
                    'hfnu.posts.view'=>'on',
                    'hfnu.posts.rss'=>'on'
                   ),
        //members
        'members'=>array( 'hfnu.forum.list'=>'on',
                    'hfnu.forum.view'=>'on',
                    'hfnu.posts.create'=>'on',
                    'hfnu.posts.edit.own'=>'on',
                    'hfnu.posts.list'=>'on',
                    'hfnu.posts.notify'=>'on',
                    'hfnu.posts.reply'=>'on',
                    'hfnu.posts.quote'=>'on',
                    'hfnu.posts.view'=>'on',
                    'hfnu.posts.rss'=>'on'
                   ),
                );
    /**
     * reset/set default rights
     * @param integer $id_forum the id_forum.
     */
    public static function resetRights($id_forum) {
        // default 'normal' rights for a given forum.
        $id_forum = (int) $id_forum;
        $rights = self::$__defaultRights;

        foreach(jAcl2DbUserGroup::getGroupList() as $grp) {
            $id = $grp->id_aclgrp;
            self::setRightsOnForum($id, (isset($rights[$id])?$rights[$id]:array()),'forum'.$id_forum);
        }
        self::setRightsOnForum('__anonymous', $rights['__anonymous'],'forum'.$id_forum);
    }

    /**
     * set rights on the given forum
     * @param integer $group the group id.
     * @param array $rights list of rights key = subject, value = true
     * @param string $resource the resource corresponding to the "forum" string + id_forum
     */
    public static function setRightsOnForum($group, $rights, $resource){
        $dao = jDao::get('jacl2db~jacl2rights', jAcl2Db::getProfile());
        $dao->deleteHfnuByGroup($group,$resource);
        foreach($rights as $sbj=>$val){
            if($val != '') {
              jAcl2DbManager::addRight($group,$sbj,$resource);
            }
        }
        jAcl2::clearCache();

    }
}
