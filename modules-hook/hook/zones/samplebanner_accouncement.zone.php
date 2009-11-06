<?php
/**
* @package   havefnubb
* @subpackage hook
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class samplebanner_accouncementZone extends jZone {
    protected $_tplname='zone.samplebanner_accouncement';
    
    protected function _prepareTpl(){    
        $this->_tpl->assign('text',jLocale::get('hook~main.welcome'));
    }        
}