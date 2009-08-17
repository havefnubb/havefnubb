<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuaboutZone extends jZone {
    protected $_tplname='zone.hfnuabout';
    
    protected function _prepareTpl(){
        $ev = jEvent::notify('HfnuAboutModule');        
        $moduleInfos = $ev->getResponse();
        $this->_tpl->assign('moduleInfos',$moduleInfos);
    }
}