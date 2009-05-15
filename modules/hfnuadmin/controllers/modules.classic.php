<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class modulesCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>true,
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
		return $rep;
	}
}   