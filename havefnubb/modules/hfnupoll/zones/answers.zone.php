<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class answersZone extends jZone {

    protected $_tplname='zone.answers';
    
    protected function _prepareTpl(){
        
        $id_poll = (int) $this->param('id_poll');
		if ( $id_poll == 0) return;
 
        $dao = jDao::get('hfnupoll~poll_answer');        
		$answers = $dao->findByIdPoll($id_poll);

        $this->_tpl->assign('id_poll',$id_poll);        
        $this->_tpl->assign('answers',$answers);
        $this->_tpl->assign('count',$answers->rowCount());

    }    
}