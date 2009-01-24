<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class lastpostsZone extends jZone {
    protected $_tplname='zone.lastposts';

    protected function _prepareTpl(){
        
        $dao = jDao::get('havefnubb~posts');
        //last posts
        $lastPost   = $dao->getLastPosts();        
        $this->_tpl->assign('lastPost',$lastPost);        
    }
}