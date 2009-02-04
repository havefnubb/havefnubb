<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class forumCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'create'    => array( 'jacl2.right'=>'hfnu.admin.forum.create'),
        'edit'      => array( 'jacl2.right'=>'hfnu.admin.forum.edit'),
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.forum.delete'),
    );
    

    function create () {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('action','hfnuadmin~forum:savecreate');
        $tpl->assign('forum_heading',jLocale::get('hfnuadmin~forum.create.a.forum'));
        $rep->body->assign('MAIN',$tpl->fetch('forum_edit'));
        return $rep;    
    }

    function savecreate () {
    
    }

    function edit () {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('action','hfnuadmin~forum:savecreate');
        $tpl->assign('forum_heading',jLocale::get('hfnuadmin~forum.edit.a.forum'));
        $rep->body->assign('MAIN',$tpl->fetch('forum_edit'));
        return $rep;        
    }

    function saveedit () {
    
    }

    
}   