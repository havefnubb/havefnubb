<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010-2021 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * the status of a member
 * @zoneparameter login the login of the user
 */
class onlinestatusZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.onlinestatus';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $login = $this->param('login');
        $status = 'offline';
        if ($login) {
            if ( jClasses::getService('activeusers~connectedusers')->isConnected($login))
                $status = 'online';
            else
                $status = 'offline';
        }
        $this->_tpl->assign('status',$status);
    }
}
