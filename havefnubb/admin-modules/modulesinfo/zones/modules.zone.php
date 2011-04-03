<?php
/**
* @package   havefnubb
* @subpackage modulesinfo
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class modulesZone extends jZone {
    protected $_tplname='zone.modules';

    protected function _prepareTpl(){
        $modulesList = jClasses::getService('modulesinfo~modulexml')->getList();
        $this->_tpl->assign('modulesList', $modulesList);
    }
}
