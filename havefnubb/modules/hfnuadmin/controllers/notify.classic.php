<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class notifyCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.right'=>'hfnu.admin.notify.list'),
        'delete'   => array( 'jacl2.right'=>'hfnu.admin.notify.delete'),
    );
    
    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~notify_index'));
        return $rep;	
    }
    

    function create () {
    
    }

    function savecreate () {
    
    }

    function edit () {
    
    }

    function saveedit () {
    
    }

    
}   