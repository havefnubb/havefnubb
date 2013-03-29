<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
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
     *@var boolean $_useCache set the zone in a cache
     */
    protected $_useCache = true;
    /**
     *@var integrer $_cacheTimeout set timeout to each hour
     */
    protected $_cacheTimeout = 3600;
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $dao = jDao::get('havefnubb~threads_stats');
        $admin = (boolean) $this->param('admin');
        //last 'x' posts
        if ( $admin )
            $lastPost  = $dao->findLastPosts( (int) jApp::config()->havefnubb['stats_nb_of_lastpost']);
        else
            $lastPost  = $dao->findLastVisiblePosts( (int) jApp::config()->havefnubb['stats_nb_of_lastpost']);

        $this->_tpl->assign('lastPost',$lastPost);
    }
}
