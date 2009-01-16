<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postprofileZone extends jZone {
    protected $_tplname='postprofile';

    protected function _prepareTpl(){
        $id_post = $this->param('id_post');
        if (!$id_post) return;

        $dao = jDao::get('posts');      
        $userPost = $dao->findIdUserByIdPost($id_post);
        
        $dao = jDao::get('member');
        $user = $dao->getById($userPost->id_user);

        $this->_tpl->assign('user',$user);
    }
}