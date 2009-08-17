<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumchildZone extends jZone {
    protected $_tplname='zone.forumchild';

    protected function _prepareTpl(){
        
        $id_forum   = $this->param('id_forum');
        $lvl        = $this->param('lvl');
        
        if (! $id_forum ) return;
        if (! $lvl ) return;
        
        $lvl = (int) $lvl;
        $id_forum = (int) $id_forum;
        
        $dao = jDao::get('havefnubb~forum');

        $forumChildren = $dao->findChild($id_forum,$lvl);
        $arrow = '';
        $arrow = str_pad($arrow,$lvl,'-') . '>';

        $this->_tpl->assign('lvl',$lvl);
        $this->_tpl->assign('arrow',$arrow);
        $this->_tpl->assign('forumChildren',$forumChildren);
    }
}