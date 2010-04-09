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
 * class that manages the display of the information of the last comment !
 */
class postlcZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.postlc';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		$id_post = $this->param('id_post');
		$id_forum = $this->param('id_forum');
		if (!$id_post and !$id_forum) return;

		$dao = jDao::get('havefnubb~posts');
		if ($id_post) {
			if (  jAcl2::check('hfnu.admin.post') ) {
				$userPost = $dao->getUserLastCommentOnPosts($id_post);
			}
			else {
				$userPost = $dao->getUserLastVisibleCommentOnPosts($id_post);
			}
		}

		if ($id_forum) {
			if (  jAcl2::check('hfnu.admin.post') ) {
				$userPost = $dao->getUserLastCommentOnForums($id_forum);
			}
			else {
				$userPost = $dao->getUserLastVisibleCommentOnForums($id_forum);
			}
		}

		$user = '';
		$noMsg = '';

		$dao = jDao::get('havefnubb~member');
		if ($userPost)
			$user = $dao->getById($userPost->id_user);
		else
			$noMsg = jLocale::get('havefnubb~forum.postlc.no.msg');

		$this->_tpl->assign('user',$user);
		$this->_tpl->assign('post',$userPost);
		$this->_tpl->assign('msg',$noMsg);
	}
}
