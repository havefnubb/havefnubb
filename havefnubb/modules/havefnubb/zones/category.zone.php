<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class categoryZone extends jZone {
    protected $_tplname='category';

    protected function _prepareTpl(){
        $dao = jDao::get('category');
        $categories = $dao->findAll();
        $this->_tpl->assign('action','index');
        $this->_tpl->assign('categories',$categories);
    }
}