<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=> array('auth.required'=>false),
        //'*'     => array('history.add'=>false),
		'index' => array('history.add'=>true, 'history.label'=>'Accueil', 'history.title'=>'Aller vers la page d\'accueil')
    );
    
    function index() {
		global $HfnuConfig;
        $title = $HfnuConfig->getValue('title','board');
        $rep = $this->getResponse('html');
		
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title));
		
        $rep->body->assignZone('MAIN', 'category');
        return $rep;
    }    
    
}

