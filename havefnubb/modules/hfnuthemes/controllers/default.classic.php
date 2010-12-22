<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Controller that manage the Theme Manager
 */
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'	=>	array('auth.required'=>true,
                          'banuser.check'=>true,
                    ),
        '*' => array( 'jacl2.right'=>'hfnu.admin.index'),
    );
    /**
     * Index that will display all the available theme to be used
     */
    function index() {
        global $gJConfig;
        $tpl = new jTpl();
        $themes = jClasses::getService('themes');
        $lists = $themes->lists();
        $tpl->assign('themes',$lists);
        $tpl->assign('lang',$gJConfig->locale);
        $tpl->assign('current_theme',strtolower($gJConfig->theme));
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('theme'));
        $rep->body->assign('selectedMenuItem','theme');
        return $rep;
    }
    /**
     * Let use one of the available theme
     */
    function useit() {
        $theme = (string) $this->param('theme');
        $mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
        $mainConfig->setValue('theme',strtolower($theme));
        $mainConfig->setValue('datepicker',strtolower($theme),'forms');
        $mainConfig->save();
        jFile::removeDir(JELIX_APP_TEMP_PATH, false);
        jMessage::add(jLocale::get('theme.selected'),'information');
        $rep = $this->getResponse('redirect');
        $rep->action = 'default:index';
        return $rep;
    }
}
