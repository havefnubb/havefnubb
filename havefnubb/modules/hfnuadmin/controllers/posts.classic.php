<?php
/**
 * Controller that handle everything about the posts
 *
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postsCtrl extends jController {
	public $pluginParams = array(
		'*' => array('auth.required'=>true,
			'hfnu.check.installed'=>true,
			'banuser.check'=>true,
					'jacl2.right'=>'hfnu.admin.index'),
	);

	public function unread() {
		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$tpl->assign('posts',jClasses::getService('havefnubb~hfnuposts')->findUnreadThreadByMod());
		$rep->body->assign('MAIN',$tpl->fetch('posts.list'));
		return $rep;
	}
}
