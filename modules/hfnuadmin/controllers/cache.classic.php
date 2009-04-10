<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class cacheCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),	
        'index' => array( 'jacl2.right' =>'hfnu.admin.cache.clear'),
        'clear' => array( 'jacl2.right' =>'hfnu.admin.cache.clear')
    );
    
    function index() {
        $form = jForms::create('hfnuadmin~cache');
        $tpl = new jTpl();
        $tpl->assign('form',$form);		
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~cache_index'));
        return $rep;	
    }

    function clear() {
        $confirm = $this->param('confirm');
        
        if ($confirm == 'Y'){
			jFile::removeDir(JELIX_APP_TEMP_PATH, false);
			jMessage::add(jLocale::get('hfnuadmin~admin.cache.clear.done'));
		}
		else {
			jMessage::add(jLocale::get('hfnuadmin~admin.cache.clear.canceled'));
		}
        $rep = $this->getResponse('redirect');
        $rep->action = 'hfnuadmin~cache:index';
        return $rep;
    }    
}   