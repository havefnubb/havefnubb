<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class categoryZone extends jZone {
    protected $_tplname='zone.category';

    protected function _prepareTpl(){
        $dao = jDao::get('havefnubb~category');
        $categories = $dao->findAll();
        $this->_tpl->assign('categories',$categories);
    }
}