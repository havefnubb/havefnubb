<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnutasktodoZone extends jZone {
    protected $_tplname='zone.hfnutasktodo';
    
    protected function _prepareTpl(){
        $ev = jEvent::notify('HfnuTaskTodo');        
        $tasks = $ev->getResponse();
        $this->_tpl->assign('tasks',$tasks);
    }
}