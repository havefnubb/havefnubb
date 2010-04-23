<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008 FoxMaSk, 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage flood events
*/
class floodCtrl extends jController {
	/**
	 * handle a possible flood protection from the same IP user
	 */
	function error() {
        global $gJConfig;
        $resp = $this->getResponse('html');
        $title = stripslashes($gJConfig->havefnubb['title']);

        $history = $GLOBALS['gJCoord']->getPlugin('history');
        $history->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
        $history->change('title', jLocale::get('havefnubb~main.goto_homepage'));

        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('havefnubb~flood.detected'));
        $resp->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
        return $resp;
	}
}
