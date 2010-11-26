<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the online members
 */
class onlineusersZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.onlineusers';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){

        $timeout = jClasses::create('activeusers~connectedusers')->getVisitTimeout();
        if ($timeout == 0)
            $timeout = time();
        $dao = jDao::get('activeusers~connectedusers');
        $members = $dao->findConnected($timeout);
        $nbMembers = $members->rowCount();
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',$nbMembers);
    }
}
