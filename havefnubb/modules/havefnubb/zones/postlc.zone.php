<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * class that manages the display of the information of the last comment !
 */
class postlcZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.postlc';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){

        $parent_id = (int) $this->param('parent_id');
        $id_forum = (int) $this->param('id_forum');
        if (!$parent_id and !$id_forum) return;

        $user = '';
        $noMsg = '';

        $dao = jDao::get('havefnubb~threads');
        if ($parent_id) {
            if (  jAcl2::check('hfnu.admin.post') ) {
                $userPost = $dao->get($parent_id);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnPosts($parent_id);
            }

            if ($userPost->nb_replies > 0)
                $user = jDao::get('havefnubb~member')->getById($userPost->id_user);
            else
                $noMsg = jLocale::get('havefnubb~forum.postlc.no.msg');
        }

        if ($id_forum) {
            if (  jAcl2::check('hfnu.admin.post') ) {
                $userPost = $dao->getUserLastCommentOnForums($id_forum);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnForums($id_forum);
            }
            $user = jDao::get('havefnubb~member')->getById($userPost->id_user);

            if ($userPost === false) $noMsg = jLocale::get('havefnubb~forum.postlc.no.msg');
        }

        $this->_tpl->assign('user',$user);
        $this->_tpl->assign('post',$userPost);
        $this->_tpl->assign('msg',$noMsg);
    }
}
