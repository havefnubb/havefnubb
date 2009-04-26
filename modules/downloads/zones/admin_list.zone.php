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

class admin_listZone extends jZone {

    protected $_tplname='admin_list';

    protected function _prepareTpl(){
        
        $dao = jDao::get('downloads~downloads');
        $allDownloads = $dao->findAll();
        
        $message = jMessage::get('admin_msg');
        $nb_msg = count($message);
        jMessage::clearAll();
        
        $this->_tpl->assign('downloads',$allDownloads);
        $this->_tpl->assign('message',$message);
        $this->_tpl->assign('nb_msg',$nb_msg);	        
        
    }
}