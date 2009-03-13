<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class banCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.right'=>'hfnu.admin.ban'),
    );
 
    function index() {
        $tpl = new jTpl();
        $rep = $this->getResponse('html');
		$form = jForms::create('hfnuadmin~bans');
		$tpl->assign('form',$form);		
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAll();
		$tpl->assign('bans',$bans);
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~bans_edit'));
        return $rep;	
    }

    function saveban () {
		$username = $this->param('ban_username');
		$ip = $this->param('ban_ip');
		$mail = $this->param('ban_email');
		$expire = $this->param('ban_expire');
		$message = $this->param('ban_message');
		
		
		if ($username == '' and $ip == '' and $mail == ''
			and $expire['day'] == '' and $expire['month'] == '' and $expire['year'] == '' 
			and $message == '' ) {
			jMessage::add(jLocale::get('hfnuadmin~ban.you.have.to.fill.one.field.at.least'),'error');
			$rep = $this->getResponse('redirect');
			$rep->action = 'hfnuadmin~ban:index';
			return $rep;
		}
		
		$submit = $this->param('validate');
        if ($submit == jLocale::get('hfnuadmin~ban.saveBt') ) {
			
			$dao 	= jDao::get('havefnubb~bans');
			$form 	= jForms::fill('hfnuadmin~bans');
			$form->saveToDao('havefnubb~bans');
			
			jMessage::add(jLocale::get('hfnuadmin~ban.added'),'ok');
			
			$rep = $this->getResponse('redirect');
			$rep->action='hfnuadmin~ban:index';
			return $rep;
		}
    
    }
    
}   