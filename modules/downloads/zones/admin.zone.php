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

class adminZone extends jZone {

    protected $_tplname='admin_index';

    protected function _prepareTpl(){
        jClasses::inc('readmodule');
        $moduleInfo = readmodule::readModuleXml();
        
        $this->_tpl->assign('moduleInfo',$moduleInfo);        
    }
}