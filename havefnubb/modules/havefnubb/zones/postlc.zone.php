<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
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

        $thread_id = (int) $this->param('thread_id');
        $id_forum = (int) $this->param('id_forum');
        if (!$thread_id and !$id_forum) return;

        $user = '';
        $noMsg = '';

        $dao = jDao::get('havefnubb~threads');
        
        $admin = jAcl2::check('hfnu.admin.post');
        
        if ($thread_id) {
            if (  $admin ) {
                $userPost = $dao->getUserLastCommentOnPosts($thread_id);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnPosts($thread_id);
            }
            $user = jDao::get('havefnubb~member')->getById($userPost->id_user);
        }
        else if ($id_forum) {
            if (  $admin ) {
                $userPost = $dao->getUserLastCommentOnForums($id_forum);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnForums($id_forum);
            }
            if ($userPost) {
                $user = jDao::get('havefnubb~member')->getById($userPost->id_user);
            }
            else {
                $noMsg = jLocale::get('havefnubb~forum.postlc.no.msg');
            }
        }

        $this->_tpl->assign('user',$user);
        $this->_tpl->assign('post',$userPost);
        $this->_tpl->assign('msg',$noMsg);
    }
}
