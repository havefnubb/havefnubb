<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumZone extends jZone {
    protected $_tplname='zone.forumindex';

    protected function _prepareTpl(){
        $id_cat = $this->param('id_cat');
        $action = $this->param('action');        
        if (! $id_cat ) return;
        if (! $action ) return;        
        
        $forums = jClasses::getService('havefnubb~hfnuforum')->findParentByCatId($id_cat);
        $this->_tpl->assign('forums',$forums);
    }
}