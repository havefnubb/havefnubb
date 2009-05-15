<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class aboutZone extends jZone {
    protected $_tplname='zone.about';
    
    protected function _prepareTpl(){
        $moduleName = $this->param('modulename');

        if ($moduleName == '') return;
        jClasses::inc('havefnubb~modulexml');
        $moduleInfo = modulexml::parse($moduleName);         
        $this->_tpl->assign('moduleInfo',$moduleInfo);  		
    }
}