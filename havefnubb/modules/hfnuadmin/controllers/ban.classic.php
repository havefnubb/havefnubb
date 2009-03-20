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

		$form = jForms::create('hfnuadmin~bans');				
		$dao = jDao::get('havefnubb~bans');
		$bans = $dao->findAll();
		
		$tpl = new jTpl();
		$tpl->assign('form',$form);
		$tpl->assign('bans',$bans);
		$rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~bans_edit'));
        return $rep;	
    }

    function saveban () {
		$username 	= $this->param('ban_username');
		$ip 		= $this->param('ban_ip');
		$mail 		= $this->param('ban_email');
		$expire 	= $this->param('ban_expire');
		$message 	= $this->param('ban_message');		
		
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
					
			if ( $ip != '' and jClasses::getService('havefnubb~bans')->checkIp($ip) === false ) {
				$rep = $this->getResponse('redirect');
				$rep->action='hfnuadmin~ban:index';
				return $rep;			
			}

			if ($mail != '') {
				$validMail = false;
				// ban one given domain
				if (  preg_match('/^\w.[a-zA-Z]{3}$/',$mail)) {
					$validMail = true;
				}
				// ban one member email
				$validMail = jFilter::isEmail($mail);
				if ( $validMail  === false ) {
					jMessage::add(jLocale::get('hfnuadmin~ban.mail.invalid'),'warning');
					$rep = $this->getResponse('redirect');
					$rep->action='hfnuadmin~ban:index';
					return $rep;											
				}
				
			}
			
			if (!empty($expire)) {

				$expire['hour'] 	= 0;
				$expire['minute']	= 0;
				$expire['second']	= 0;
				$expire['day'] 		= (int) $expire['day'];
				$expire['month'] 	= (int) $expire['month'];
				$expire['year'] 	= (int) $expire['year'];
				
				
				$now = mktime(0,0,0,date('m'), date('d'),date('Y'));
				$expiry = mktime($expire['hour'],$expire['minute'],$expire['second'],$expire['month'] ,$expire['day'],$expire['year']);
				
				if ($expiry <= $now ) {
					jMessage::add(jLocale::get('hfnuadmin~ban.expiry.invalid'),'warning');
					$rep = $this->getResponse('redirect');
					$rep->action='hfnuadmin~ban:index';
					return $rep;					
				}
			}
			
			$dao 	= jDao::get('havefnubb~bans');
			$form 	= jForms::fill('hfnuadmin~bans');
			$form->saveToDao('havefnubb~bans');
			
			jMessage::add(jLocale::get('hfnuadmin~ban.added'),'ok');
			
			$rep = $this->getResponse('redirect');
			$rep->action='hfnuadmin~ban:index';
			return $rep;
		}
    
    }
    function delete () {
		$ban_id = $this->param('ban_id');
		$dao = jDao::get('havefnubb~bans');
		$dao->delete($ban_id);
		jMessage::add(jLocale::get('hfnuadmin~ban.deleted'),'ok');
		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~ban:index';
		return $rep;		
	}
	
}   