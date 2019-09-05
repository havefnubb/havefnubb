<?php
/**
* @package   havefnubb
* @subpackage modulesinfo
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class modulesZone extends jZone {
    protected $_tplname='zone.modules';

    protected function _prepareTpl()
    {

        $modulesList = array();
        foreach (jApp::getEnabledModulesPaths() as $name => $path) {
            $modulesList[$name] = \Jelix\Core\Infos\ModuleInfos::load($path);
        }
        $this->_tpl->assign('modulesList', $modulesList);
    }
}
