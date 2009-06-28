<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class floodCtrl extends jController {
    /**
    *
    */
    function sameip() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title','main'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto.homepage'));
		$rep = $this->getResponse('html');		
        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('havefnubb~flood.sameip'));
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
        return $rep;		
	}
 
    function editing() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title','main'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto.homepage'));
		$rep = $this->getResponse('html');		
        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('havefnubb~flood.editing'));
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
        return $rep;		
	}
}