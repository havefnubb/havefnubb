<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class memberprofileZone extends jZone {
    protected $_tplname='zone.memberprofile';

    protected function _prepareTpl(){
        $id = $this->param('id');
        if (!$id) return;

        $dao = jDao::get('havefnubb~member');
        $user = $dao->getById($id);

        $this->_tpl->assign('user',$user);
    }
}