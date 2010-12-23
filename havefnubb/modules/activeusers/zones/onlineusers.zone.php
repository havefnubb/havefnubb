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

        $members = jClasses::create('activeusers~connectedusers')->getList();
        $nbMembers = $members->rowCount();
        $this->_tpl->assign('members',$members);
        $this->_tpl->assign('nbMembers',$nbMembers);
    }
}