<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class catviewZone extends jZone {
    protected $_tplname='category';

    protected function _prepareTpl(){
		jClasses::inc('title');
		$title = title::board();
        
        $id_cat = $this->param('id_cat');
        $dao = jDao::get('category');        
        $category = $dao->get($id_cat);
        
        $this->title = $title . ' - '. htmlentities($category->cat_name);
        
        echo $this->title;
        
        $this->_tpl->assign('action','view');
        $this->_tpl->assign('category',$category);
    }
}