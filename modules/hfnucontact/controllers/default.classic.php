<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {

    public $pluginParams = array(

        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),        
    );    
    
    public function index() {
		$to = $this->param('to');

		if ($to != '') {			
			$dao = jDao::get('jcommunity~user');
			$user = $dao->getByLogin($to);

			if ($user === false) {
				throw new jException('hfnucontact~contact.contact.does.not.exist');
			}
		}
		else {
			$HfnucontactConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'hfnucontact.ini.php');
			$to = $HfnucontactConfig->getValue('email_contact');
		}
		// is the 'To' still empty ??
		if ( $to == '' ) {
			throw new jException('hfnucontact~contact.email.config.not.done.properly');		 			
		}
		
		$form = jForms::create('hfnucontact~contact');
		$form->setData('to',$to);
        $rep = $this->getResponse('html');        
        $tpl = new jTpl();		
		$tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('hfnucontact~contact'));
        return $rep;
    }
	
	public function send_a_message() {
		
		$form = jForms::fill('hfnucontact~contact');

		if (! $form->check()) {
			$to = $this->param('to');
			jMessage::add($form->getErrors(),'error');
			$rep = $this->getResponse('redirect');
			$rep->action='hfnucontact~default:index';
			$rep->params = array('to',$to);
			return $rep;			
		}
		
		$mail = new jMailer();
		$mail->From       = jAuth::getUserSession ()->email;
		$mail->FromName   = jAuth::getUserSession ()->login;
		$mail->Sender     = jAuth::getUserSession ()->email;
		$mail->Subject    = $form->getData('subject');
		$mail->ContentType = 'text/html';
		
		$tpl = new jTpl();
		$tpl->assign('login',jAuth::getUserSession ()->login);
		$tpl->assign('message',$form->getData('message'));
		$mail->Body = $tpl->fetch('hfnucontact~send_an_email', 'text');

		$dao = jDao::get('jcommunity~user');
		$user = $dao->getByLogin($form->getData('to'));		
		$mail->AddAddress($user->email);
		$mail->Send();
		
		jForms::destroy('hfnucontact~contact');
        $rep = $this->getResponse('redirect');        
        $rep->action='hfnucontact~default:contacted';
        return $rep;		
	}
	
    public function contacted() {
        $rep = $this->getResponse('html');        
        $tpl = new jTpl();		
        $rep->body->assign('MAIN',$tpl->fetch('hfnucontact~contacted'));
        return $rep;
    }	
}

