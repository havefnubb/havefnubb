<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
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
            
            $id_post    = (int) $this->param('id_post');
            $id_forum   = (int) $this->param('id_forum');

            if ($id_post < 1) return;
            if ($id_forum < 1) return;            

            $rec = $dao->getPostStatus($id_post);
            
            $day_in_secondes = 24 * 60 * 60;
    		$dateDiff =  ($rec->date_modified == '') ? floor( (time() - $rec->date_created ) / $day_in_secondes) : floor( (time() - $rec->date_modified ) / $day_in_secondes) ;
            
            // lets find in the forum
            // how many time the thread can stay open in post_expire
            $daoForum = jDao::get('havefnubb~forum');    
            $recForum = $daoForum->get($id_forum);
          
            if ( $rec === false ) {                
                
                if ( $recForum->post_expire > 0 and $dateDiff >= $recForum->post_expire )
                    $status = 'closed';

                else 
                    $status = $this->param('status');
            } 
            else {
                
                if ( $recForum->post_expire > 0 and $dateDiff >= $recForum->post_expire )
                    $status = 'closed';

                else                 
                    $status = 'post-new';
            }
        }

        $this->_tpl->assign('post_status',$status);
    }
}