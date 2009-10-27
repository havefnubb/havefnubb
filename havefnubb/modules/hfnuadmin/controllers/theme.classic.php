<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class themeCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'	=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),
		'*' => array( 'jacl2.right'=>'hfnu.admin.index'),
    );
    
    function index() {        
        $tpl = new jTpl();
        $themes = jClasses::getService('hfnuadmin~themes');       
        $tpl->assign('themes',$themes->lists());
        $rep = $this->getResponse('html');                
        $rep->body->assign('MAIN',$tpl->fetch('theme'));
		$rep->body->assign('selectedMenuItem','theme');
        return $rep;
	}

}   