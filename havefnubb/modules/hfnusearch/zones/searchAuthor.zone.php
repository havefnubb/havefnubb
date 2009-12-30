<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class searchAuthorZone extends jZone {
	protected $_tplname='zone.searchAuthor';

	protected function _prepareTpl(){
		$form = jForms::create('hfnusearch~author');
		$form->setDAta('perform_search_in','authors');
		$this->_tpl->assign('form',$form);
	}
}
