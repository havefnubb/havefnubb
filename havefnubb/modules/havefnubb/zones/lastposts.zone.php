<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the last 'x' posts
 */
class lastpostsZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.lastposts';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        global $gJConfig;
        $dao = jDao::get('havefnubb~threads');
        //last 'x' posts
        if ( jAcl2::check('hfnu.admin.post') )
            $lastPost  = $dao->findLastPosts( (int) $gJConfig->havefnubb['stats_nb_of_lastpost']);
        else
            $lastPost  = $dao->findLastVisiblePosts( (int) $gJConfig->havefnubb['stats_nb_of_lastpost']);
        
        $this->_tpl->assign('lastPost',$lastPost);
    }
}
