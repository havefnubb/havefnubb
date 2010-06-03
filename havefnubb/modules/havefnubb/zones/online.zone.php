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
 * Class the displays the online members
 */
class onlineZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.online';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){

        $dao = jDao::get('havefnubb~member');
        $members = $dao->findAllConnected(time());
        $nbMembers = $members->rowCount();
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',$nbMembers);
    }
}
