<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class ranks_edit_inlineZone extends jZone {
    protected $_tplname='zone.ranks_edit_inline';

    protected function _prepareTpl(){
        $id_rank = $this->param('id_rank');
        if (!$id_rank) return;
        
        $form = jForms::create('hfnuadmin~ranks',$id_rank);
        $form->initFromDao("havefnubb~ranks");
                
        $form->setData('id_rank',$id_rank);
        
        $this->_tpl->assign('id_rank',$id_rank);       
        $this->_tpl->assign('form',$form);
    }
}