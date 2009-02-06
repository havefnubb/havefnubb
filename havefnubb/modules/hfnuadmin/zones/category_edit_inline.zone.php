<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class category_edit_inlineZone extends jZone {
    protected $_tplname='zone.category_edit_inline';

    protected function _prepareTpl(){
        $id_cat = $this->param('id_cat');
        if (!$id_cat) return;
        
        $form = jForms::create('hfnuadmin~category',$id_cat);
        $form->initFromDao("havefnubb~category");
                
        $form->setData('id_cat',$id_cat);
        
        $this->_tpl->assign('id_cat',$id_cat);       
        $this->_tpl->assign('form',$form);
    }
}