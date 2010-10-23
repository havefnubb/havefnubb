<?php
/**
* @package   jelix_admin_modules
* @subpackage master_admin
* @author    Laurent Jouanneau
* @copyright 2008 Laurent Jouanneau
* @link      http://jelix.org
* @licence  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public Licence, see LICENCE file
*/


class defaultCtrl extends jController {

    public $pluginParams = array(
        '*'=>array('auth.required'=>true),
     );

    /**
     *
     */
    function index() {
        $resp = $this->getResponse('html');
        $resp->title = jLocale::get('gui.dashboard.title');
        $resp->body->assignZone('MAIN','dashboard');
        $user = jAuth::getUserSession();
        if ($user->login == 'admin' && ($user->password == md5('admin') || $user->password == sha1('admin'))) {
            jMessage::add(jLocale::get('gui.message.admin.password'), 'error');
        }
        $resp->body->assign('selectedMenuItem','dashboard');
        return $resp;
    }
}
