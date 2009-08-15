<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// class that manages the display of the pined posts
class pinedpostsZone extends jZone {
    protected $_tplname='zone.pinedposts';

    protected function _prepareTpl(){
        
        $id_forum = $this->param('id_forum');        
        if (!$id_forum) return;
        
        $dao = jDao::get('havefnubb~posts');      
        $posts = $dao->findPinedPostByIdForum($id_forum);
        $this->_tpl->assign('posts',$posts);
    }
}