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
 * Class the displays the member profile
 */
class memberprofileZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.memberprofile';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $user = $this->param('user');
        $id = $this->param('id');

        if ($id && !$user) {
            $dao = jDao::get('havefnubb~member');
            $user = $dao->getById($id);
            $this->_tpl->assign('user',$user);
        }
    }
}
