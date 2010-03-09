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
 * class that manages the display of the information of nb of views for a thread
 */
class viewedttlZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.viewedttl';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		$id_post = $this->param('id_post');
		if (!$id_post) return;

		$dao = jDao::get('havefnubb~posts');
		$viewedttl = $dao->getNbOfViewed($id_post);

		$this->_tpl->assign('viewedttl',$viewedttl->viewed);
	}
}
