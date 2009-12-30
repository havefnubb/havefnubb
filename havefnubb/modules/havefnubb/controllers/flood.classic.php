<?php
/**
* Controller to manage flood events
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class floodCtrl extends jController {
	/**
	 * handle a possible flood protection from the same IP user
	 */		
	function sameip() {
		global $gJConfig;
		$title = stripslashes($gJConfig->havefnubb['title']);
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));
		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$tpl->assign('message',jLocale::get('havefnubb~flood.sameip'));
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
		return $rep;
	}
	/**
	 * handle a possible flood protection for editing a post
	 */
	function editing() {
		global $gJConfig;
		$title = stripslashes($gJConfig->havefnubb['title']);
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
		$GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));
		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$tpl->assign('message',jLocale::get('havefnubb~flood.editing'));
		$rep->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
		return $rep;
	}
}