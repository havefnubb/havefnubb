<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumZone extends jZone {
    protected $_tplname='zone.forumindex';

    protected function _prepareTpl(){
        $id_cat = $this->param('id_cat');
        if (! $id_cat ) return;

        $dao = jDao::get('havefnubb~forum');

        $forums = $dao->findParentByCatId($id_cat);
        $this->_tpl->assign('tableclass','forumList');

        $this->_tpl->assign('forums',$forums);
    }
}
