<?php
/**
* @package      downloads
* @subpackage
* @author       foxmask
* @contributor foxmask
* @copyright    2008 foxmask
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class adminconfigZone extends jZone {

    protected $_tplname='admin_config';

    protected function _prepareTpl(){
        
        $form = $this->getParam('form', false);
        if (!$form) return;
        
        $message = jMessage::get('admin_msg');
        $nb_msg = count($message);
        jMessage::clearAll();

        $this->_tpl->assign('form',$form);
        $this->_tpl->assign('message',$message);
        $this->_tpl->assign('nb_msg',$nb_msg);	
    }
}