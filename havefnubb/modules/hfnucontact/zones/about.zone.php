<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class aboutZone extends jZone {
    protected $_tplname='zone.about';
    
    protected function _prepareTpl(){
        jClasses::inc('havefnubb~modulexml');
        $moduleInfo = modulexml::parse('hfnucontact');        
        $this->_tpl->assign('moduleInfo',$moduleInfo);  		
    }
}