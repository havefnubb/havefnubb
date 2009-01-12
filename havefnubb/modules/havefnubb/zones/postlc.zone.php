<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// class that manages the display of the information of the last comment !
class postlcZone extends jZone {
    protected $_tplname='postlc';

    protected function _prepareTpl(){
        
        $id_post = $this->param('id_post');
        $id_forum = $this->param('id_forum');        
        if (!$id_post and !$id_forum) return;
        
        $dao = jDao::get('posts');
        if ($id_post) {        
            $user_post = $dao->getUserLastCommentOnPosts($id_post);
        }
        
        if ($id_forum) {        
            $user_post = $dao->getUserLastCommentOnForums($id_forum);
        }

        $user = '';
        $no_msg = '';
        
        $dao = jDao::get('member');        
        if ($user_post)
            $user = $dao->getById($user_post->id_user);
        else
            $no_msg = jLocale::get('forum.no.msg');
            
        $this->_tpl->assign('user',$user);
        $this->_tpl->assign('post',$user_post);
        $this->_tpl->assign('msg',$no_msg);
    }
}