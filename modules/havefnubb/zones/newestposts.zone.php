<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class newestpostsZone extends jZone {
    protected $_tplname='zone.newestposts';

    protected function _prepareTpl(){
        $status = '';        
        $source = $this->param('source');

        $dao = jDao::get('havefnubb~newest_posts');
       
        if ($source == 'forum') {
            $id_forum = (int) $this->param('id_forum');            
            if ($id_forum < 1) return;
            $rec = $dao->getLastPostByIdForum($id_forum);
            
            if ( $rec === false) 
                $status = 'forumicone';
            else
                $status = 'forumiconenew';
        }
        elseif ($source =='post') {
            $availableStatus = array('opened','closed','pined','pinedclosed');
            if (! in_array($this->param('status'),$availableStatus)) return;
            $id_post = (int) $this->param('id_post');
            if ($id_post < 1) return;
            $rec = $dao->getPostStatus($id_post);
            
            if ( $rec === false ) {
                
                $status = $this->param('status');
            } 
            else {
                $status = 'post-new';
            }
        }

        $this->_tpl->assign('post_status',$status);
    }
}