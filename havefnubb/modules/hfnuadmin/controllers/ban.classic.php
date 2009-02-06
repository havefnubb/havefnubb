<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class banCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.rights.and'=>array('hfnu.admin.ban.create',
														'hfnu.admin.ban.edit')
							),
        'delete'   => array( 'jacl2.right'=>'hfnu.admin.ban.delete'),
    );
    
    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~bans_index'));
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