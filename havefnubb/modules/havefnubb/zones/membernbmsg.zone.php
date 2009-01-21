<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class membernbmsgZone extends jZone {
    protected $_tplname='zone.membernbmsg';

    protected function _prepareTpl(){
        $id_user = $this->param('id_user');
        if (!$id_user) return;

        $dao = jDao::get('posts');      
        $total = $dao->findNbMsgByIdUser($id_user);

        $this->_tpl->assign('total',$total);
    }
}