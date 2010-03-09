<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle the forum category
 */
class categoryZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.category';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){
		$data = $this->getParam('data');
		$nbCat = (int) $this->getParam('nbCat');

		$this->_tpl->assign('selectedMenuItem','community');
		$this->_tpl->assign('action','index');
		$this->_tpl->assign('categories',$data);
		$this->_tpl->assign('nbCat',$nbCat);
	}
}
