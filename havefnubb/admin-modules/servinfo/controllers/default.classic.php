<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Controller that displays some Server Informations
 */
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'	=>	array('auth.required'=>true,
                    'banuser.check'=>true,
                    'jacl2.right'=>'servinfo.access'
                    ),
    );

    /**
     * call to phpinfo
     */
    public function phpinfo() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $rep->body->assign('MAIN',$tpl->fetch('servinfo~phpinfo'));
        return $rep;
    }
}
