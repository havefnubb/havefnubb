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

class blockZone extends jZone {

    protected $_tplname='public_block';

    protected function _prepareTpl(){

        $dir = $this->getParam('dir', false);
        if (!$dir) return;
        
        $dao = jDao::get('downloads~downloads');        
        $enableDownloads = $dao->findEnabledAndOnBlock($dir);        
        
        $this->_tpl->assign('downloads',$enableDownloads);  
    }
}