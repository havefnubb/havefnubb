<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * This controller display the content of the module.xml file 
 */
class modulesCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */

	public $pluginParams = array(
		'*' => array('auth.required'=>true,
					'hfnu.check.installed'=>true,
					'banuser.check'=>true,
		  ),
		'index' => array( 'jacl2.right'=>'hfnu.admin.index'),
	);

	function index() {
		$rep = $this->getResponse('html');
		$tpl = new jTpl();

		$tpl->assign('modules',jEvent::notify('HfnuAboutModule')->getResponse());

		$rep->body->assign('MAIN',$tpl->fetch('modules'));
		$rep->body->assign('selectedMenuItem','modules');
		return $rep;
	}
}
