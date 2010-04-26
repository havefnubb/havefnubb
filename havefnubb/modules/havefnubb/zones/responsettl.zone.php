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
 * class that manages the display of the information of nb of answer for a given thread
 */
class responsettlZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.responsettl';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		$id_post = $this->param('id_post');
		if (!$id_post) return;

		$dao = jDao::get('havefnubb~posts');
		//number of posts "minus" the current post
		$responsettl = $dao->countResponse($id_post) - 1;

		if ($responsettl < 0 ) $responsettl = 0;

		$this->_tpl->assign('responsettl',$responsettl);
	}
}
