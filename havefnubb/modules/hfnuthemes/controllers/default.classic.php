<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// Theme Manager
class defaultCtrl extends jController {

    public $pluginParams = array(
        '*'	=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),
		'*' => array( 'jacl2.right'=>'hfnu.admin.index'),
    );
    
    function index() {
        global $gJConfig;
        $tpl = new jTpl();		
		$mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
		$mainConfig->getValue('theme',strtolower($theme));		
        $themes = jClasses::getService('themes');
        $lists = $themes->lists();
        $tpl->assign('themes',$lists);
        $tpl->assign('lang',$gJConfig->locale);
		$tpl->assign('current_theme',$mainConfig->getValue('theme'));
        $rep = $this->getResponse('html');                
        $rep->body->assign('MAIN',$tpl->fetch('theme'));
		$rep->body->assign('selectedMenuItem','theme');
        return $rep;
	}
	
	function useit() {
		$theme = (string) $this->param('theme');	
		$mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
		$mainConfig->setValue('theme',strtolower($theme));
		$mainConfig->save();		
		jMessage::add(jLocale::get('theme.selected'),'information');
		$rep = $this->getResponse('redirect');
		$rep->action = 'default:index';
		return $rep;
	}
}   