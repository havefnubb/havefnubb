<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle form of SearchEngine Author
 */
class searchAuthorZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.searchAuthor';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){
		$form = jForms::create('hfnusearch~author');
		$form->setDAta('perform_search_in','authors');
		$this->_tpl->assign('form',$form);
	}
}
