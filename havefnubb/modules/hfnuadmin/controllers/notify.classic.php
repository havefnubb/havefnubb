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
        $dao = jDao::get('havefnubb~notify');
        $notify = $dao->findAll();
        $tpl->assign('notify',$notify);
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~notify_index'));
        return $rep;	
    }
    

    function delete () {
        $id_notify = (int) $this->param('id_notify');
        
        $rep = $this->getResponse('redirect');
        $rep->action = 'hfnuadmin~notify:index';
        if ($id_notify == 0 ) return $rep;
        
        $dao = jDao::get('havefnubb~notify');
        $dao->delete($id_notify);
        jMessage::add(jLocale::get('hfnuadmin~notify.notify.deleted'),'ok');
        return $rep;    
    }


    
}   