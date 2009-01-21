<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// class that manages the display of the information of the last comment !
class viewedttlZone extends jZone {
    protected $_tplname='zone.viewedttl';

    protected function _prepareTpl(){
        
        $id_post = $this->param('id_post');
        if (!$id_post) return;
        
        $dao = jDao::get('posts');       
        $viewedttl = $dao->findNbOfViewed($id_post);

        $this->_tpl->assign('viewedttl',$viewedttl->viewed);
    }
}