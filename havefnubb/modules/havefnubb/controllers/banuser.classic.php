<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage any banuser events
*/
class banuserCtrl extends jController {
    /**
    * Page info display to banned users
    */
    function index() {
        $title = stripslashes(jApp::config()->havefnubb['title']);
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities( $title,ENT_COMPAT,'UTF-8') ) );
        $GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('message',jLocale::get('havefnubb~ban.you.are.banned'));
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~banuser'));
        return $rep;
    }
}
