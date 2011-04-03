<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
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

        $thread_id = (int) $this->param('thread_id');
        $id_forum = (int) $this->param('id_forum');
        if (!$thread_id and !$id_forum) return;

        $user = '';
        $noMsg = '';
        $title='';

        $dao = jDao::get('havefnubb~threads');
        if ($thread_id) {
            if (  jAcl2::check('hfnu.admin.post') ) {
                $userPost = $dao->getUserLastCommentOnPosts($thread_id);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnPosts($thread_id);
            }
            $user = jDao::get('havefnubb~member')->getById($userPost->id_user);
            $title  = $userPost->subject;
        }
        else if ($id_forum) {
            if (  jAcl2::check('hfnu.admin.post') ) {
                $userPost = $dao->getUserLastCommentOnForums($id_forum);
            }
            else {
                $userPost = $dao->getUserLastVisibleCommentOnForums($id_forum);
            }
            if ($userPost !== false) {
                $title = jClasses::getService('havefnubb~hfnuposts')->getPost(
                            jDao::get('havefnubb~threads_alone')->get($userPost->thread_id)->id_first_msg
                            )->subject;
                $user = jDao::get('havefnubb~member')->getById($userPost->id_user);
            }
            else
                $noMsg = jLocale::get('havefnubb~forum.postlc.no.msg');
        }

        $this->_tpl->assign('user',$user);
        $this->_tpl->assign('post',$userPost);
        $this->_tpl->assign('title',$title);
        $this->_tpl->assign('msg',$noMsg);
    }
}
