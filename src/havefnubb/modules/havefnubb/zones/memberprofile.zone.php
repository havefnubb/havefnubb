<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * class that displays the member profile
 */
class memberprofileZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.memberprofile';
    /**
     *@var boolean $_useCache set the zone in a cache
     */
    protected $_useCache = true;
    /**
     *@var integrer $_cacheTimeout set timeout to one h
     */
    protected $_cacheTimeout = 3600;
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $id = (int) $this->param('user_id');
        if ($id) {
            $dao = jDao::get('havefnubb~member');
            $user = $dao->getById($id);
        }
        else {
            $user = false;
        }
        $this->_tpl->assign('user', $user);
    }
}
