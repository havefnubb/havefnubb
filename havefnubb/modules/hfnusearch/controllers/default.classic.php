<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */

    function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $rep->body->assign('MAIN', $tpl->fetch('hfnusearch~search'));
        return $rep;
    }
    
    function query() {    
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN',$tpl->fetch('hfnusearch~result'));
        return $rep;
    }
}

