<?php
/**
* @package   iamhere
* @subpackage iamhere
* @author    FoxMaSk
* @copyright 2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the activity of members
*/
class defaultCtrl extends jController {

    public function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();

        $tpl->assign('recs', jDao::get('iamhere~pages')->findAll());

        $rep->body->assign('MAIN', $tpl->fetch('iamhere~index'));
        return $rep;
    }

}
