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
        '*'		=> array('auth.required'=>false,
						 'banuser.check'=>true
						 ),
		'cloud'	=> array('hfnu.check.installed'=>true),
		'index' => array('hfnu.check.installed'=>true,
						 'history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    
    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title'));
        $rep = $this->getResponse('html');
		
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title));
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto.homepage'));
		
        $rep->body->assignZone('MAIN', 'havefnubb~category');
        return $rep;
    }
	
	function cloud () {
		$tag = $this->param('tag');
		
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title'));
        $rep = $this->getResponse('html');
		
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title) . ' - ' . jLocale::get('havefnubb~main.cloud'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.cloud'));
		
        $rep->body->assignZone('MAIN', 'havefnubb~postlistbytag',array('tag'=>$tag));
        return $rep;		
	}
	
	function notinstalled() {
        $rep = $this->getResponse('html');
		$tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~notinstalled'));
        return $rep;		
	}
}

