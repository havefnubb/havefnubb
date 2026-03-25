<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019-2026 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Havefnubb\Havefnubb\Services;

/**
 * This controller manages the posts that the staff of the forum has not read
 */
class postsCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>true,
            'banuser.check'=>true,
            'jacl2.right'=>'hfnu.admin.index'),
    );

    public function unread() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('posts', Services::posts()->findUnreadThreadByMod());
        $rep->body->assign('MAIN',$tpl->fetch('posts.list'));
        return $rep;
    }
}
