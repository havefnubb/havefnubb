<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the online members of the day
 */
class online_todayZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.online_today';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $today  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
        $members = jClasses::create('activeusers~connectedusers')->getConnectedList($today);
        $this->_tpl->assign('nbAnonymous',array_shift($members));
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',count($members));
    }
}
