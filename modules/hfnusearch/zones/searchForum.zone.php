<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class searchForumZone extends jZone {
    protected $_tplname='zone.searchForum';
    
    protected function _prepareTpl(){
        $form = jForms::create('hfnusearch~forum');
        $form->setDAta('perform_search_in','forums');
        $this->_tpl->assign('form',$form);         
    }    
}