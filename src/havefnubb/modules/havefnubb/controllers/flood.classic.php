<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
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
        $resp = $this->getResponse('html');
        $title = stripslashes(jApp::config()->havefnubb['title']);

        $history = jApp::coord()->getPlugin('history');
        $history->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
        $history->change('title', jLocale::get('havefnubb~main.goto_homepage'));

        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('havefnubb~flood.detected'));
        $resp->body->assign('MAIN', $tpl->fetch('havefnubb~flood'));
        return $resp;
	}
}
