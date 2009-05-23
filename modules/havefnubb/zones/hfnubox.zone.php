<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class HfnuBoxWidget {
    public $title = '';
    public $content = '';
}

class hfnuboxZone extends jZone {
    protected $_tplname='zone.hfnubox';

    protected function _prepareTpl(){        
        $this->_tpl->assign('widgets', jEvent::notify('HfnuBoxWidget')->getResponse());
    }
}
