<?php
/**
 * @package   havefnubb
 * @subpackage activeusers_admin
 * @author    Laurent Jouanneau
 * @copyright 2021 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


/**
 * Display some statistics about connected users
 */
class activeusers_dashboardZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.activeusers';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl()
    {
        $nbMembers = jClasses::create('activeusers~connectedusers')->getCount();
        $this->_tpl->assign('usersCount', $nbMembers);
    }
}
