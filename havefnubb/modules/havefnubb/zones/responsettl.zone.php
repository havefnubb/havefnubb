<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// class that manages the display of the information of the last comment !
class responsettlZone extends jZone {
    protected $_tplname='zone.responsettl';

    protected function _prepareTpl(){
        
        $id_post = $this->param('id_post');
        if (!$id_post) return;
        
        $dao = jDao::get('havefnubb~posts');       
        $responsettl = $dao->countResponse($id_post);

        $this->_tpl->assign('responsettl',$responsettl);
    }
}