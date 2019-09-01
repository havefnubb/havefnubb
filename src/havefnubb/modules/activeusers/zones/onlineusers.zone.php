<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * displays members currently connected
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
        list($nbAnonymous, $members, $bots)  = jClasses::create('activeusers~connectedusers')->getConnectedList();

        $this->_tpl->assign('nbAnonymous',$nbAnonymous);
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',count($members));
        $this->_tpl->assign('bots',$bots);
    }
}
