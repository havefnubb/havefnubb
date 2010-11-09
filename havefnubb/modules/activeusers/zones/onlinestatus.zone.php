<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008 FoxMaSk, 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the members the status of the members
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
        $userId = (int) $this->param('userId');
        $status = 'offline';
        if ($userId > 0) {
            $dao = jDao::get('havefnubb~timeout');
            $user = $dao->getConnectedByIdUser(time(),$userId);

            if ( $user === false )
                $status = 'offline';
            else
                $status = 'online';
        }
        $this->_tpl->assign('status',$status);
    }
}
