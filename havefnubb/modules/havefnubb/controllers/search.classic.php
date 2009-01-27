<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class searchCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*' => array( 'jacl2.right'=>'hfnu.search'),
        
		'index' => array('history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    // @TODO
    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title'));
        $rep = $this->getResponse('html');				
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', jLocale::get('havefnubb~main.common.search'));	
        $rep->body->assign('MAIN', '');
        return $rep;
    }    
    
}