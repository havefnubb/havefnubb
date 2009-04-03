<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {

    public $pluginParams = array(
        '*'	=> array('auth.required'=>true),
        '*'	=> array('jacl2.right'=>'hfnu.admin.serverinfo'),
    );    
    
    public function phpinfo() {
        $rep = $this->getResponse('html');        
        $tpl = new jTpl();
        $rep->body->assign('MAIN',$tpl->fetch('servinfo~phpinfo'));
        return $rep;
    }    
}

