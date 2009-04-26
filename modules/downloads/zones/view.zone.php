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

class viewZone extends jZone {

    protected $_tplname='public_view';

    protected function _prepareTpl(){
        
        $dir = $this->getParam('dir', false);
        if (!$dir) return;
        
        jClasses::inc('download_config');
        $config = downloadConfig::getConfig();
        $allowGuest = $config->getValue('allow.guest');
        
        $filesize = $this->getParam('filesize', false);
        
        $data = $this->getParam('data', false);
        $this->_tpl->assign('path',$dir);
        $this->_tpl->assign('filesize',$filesize);
        $this->_tpl->assign('download',$data);
        $this->_tpl->assign('allowGuest',$allowGuest);	        
    }    
    
}