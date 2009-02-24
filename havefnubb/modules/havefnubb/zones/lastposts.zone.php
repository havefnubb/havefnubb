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
        global $HfnuConfig;
        $dao = jDao::get('havefnubb~posts');
        
        //last 'x' posts
        $lastPost  = $dao->findLastPosts( (int) $HfnuConfig->getValue('stats_nb_of_lastpost'));
        $this->_tpl->assign('lastPost',$lastPost);        
    }
}