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
 * Class the displays the nb of post and thread
 */
class postandmsgZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.postandmsg';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		$id_forum = $this->param('id_forum');
		if (!$id_forum) return;

		$dao = jDao::get('havefnubb~posts');

		$nb_msg = 0;
		$nb_thread = 0;

		$nbMsg = $dao->countMessages($id_forum);
		$nbThread = $dao->countThreads($id_forum);

		$this->_tpl->assign('nbMsg',$nbMsg);
		$this->_tpl->assign('nbThread',$nbThread);
	}
}
