<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postlistinpostZone extends jZone {
    protected $_tplname='zone.postlistinpost';

    protected function _prepareTpl(){
        global $HfnuConfig;
        $id_post = $this->param('id_post');
        if (!$id_post) return;
        
        $dao = jDao::get('havefnubb~posts'); 
        $posts = $dao->findChildByIdPost($id_post);
        
        $this->_tpl->assign('wr_engine',$HfnuConfig->getValue('forum_post_render','board'));
        $this->_tpl->assign('posts',$posts);
        $this->_tpl->assign('id_post',$id_post);                   
    }
}