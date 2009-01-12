<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postlistZone extends jZone {
    protected $_tplname='postlist';

    protected function _prepareTpl(){
        $id_forum = $this->param('id_forum');
        if (!$id_forum) return;
        
        $dao = jDao::get('posts');
        $posts = $dao->findByIdForum($id_forum);

        $this->_tpl->assign('posts',$posts);
    }
}