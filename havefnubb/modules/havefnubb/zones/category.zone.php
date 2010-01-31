<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class categoryZone extends jZone {
	protected $_tplname='zone.category';

	protected function _prepareTpl(){
		$data = $this->getParam('data');
		$nbCat = (int) $this->getParam('nbCat');

		$this->_tpl->assign('selectedMenuItem','community');
		$this->_tpl->assign('action','index');
		$this->_tpl->assign('categories',$data);
		$this->_tpl->assign('nbCat',$nbCat);
	}
}
