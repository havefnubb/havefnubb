<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class aboutZone extends jZone {
    protected $_tplname='zone.about';

    protected function _prepareTpl(){
        $moduleName = $this->param('modulename');

        if ($moduleName == '') return;

        $this->_tpl->assign('moduleInfo',
                            jClasses::getService('havefnubb~modulexml')->parse($moduleName)
                            );
    }
}
