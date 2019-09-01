<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle form of SearchEngine of Forum
 */
class searchForumZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.searchForum';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $form = jForms::create('hfnusearch~forum');
        $form->setDAta('perform_search_in','forums');
        $this->_tpl->assign('form',$form);
    }
}
