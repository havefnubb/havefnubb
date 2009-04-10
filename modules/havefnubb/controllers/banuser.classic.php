<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class banuserCtrl extends jController {
    /**
    *
    */
    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title'));
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($title));
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto.homepage'));
		$rep = $this->getResponse('html');		
        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('hfnuadmin~ban.you.are.banned'));
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~banuser'));
        return $rep;		
	}
}