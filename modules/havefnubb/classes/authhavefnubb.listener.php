<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class authhavefnubbListener extends jEventListener{

   /**
   *
   */
    function onAuthLogin ($event) {
        global $gJConfig;    
        $login = $event->getParam('login');

        // update the last connection access
        $dao = jDao::get('havefnubb~member');

        $user = $dao->getByLogin($login);
        if (!$user) {
          die("");
        }
        // put the current date
        $user->member_last_connect = time();
        $user->connected = time();

        $dao->update($user);
      
        $_SESSION['JX_LANG'] = $user->member_language;
        $gJConfig->locale = $user->member_language;
   }
   
    function onAuthLogout($event) {
        $_SESSION['JX_LANG'] = '';
        unset($_SESSION['JX_LANG']);
   }
   
    function onjcommunity_save_account ($event) {
        global $gJConfig;
        $form = $event->getParam('form');
        if ( $form->getData('member_language') != '') {
            $_SESSION['JX_LANG'] = $form->getData('member_language');
            $gJConfig->locale = $form->getData('member_language');
        }
        jMessage::add(jLocale::get('havefnubb~member.profile.updated'),'ok');
   }
   
    // send a mail to the admin when a user registers
    function onAuthNewUser ($event) {
        global $HfnuConfig, $gJConfig;

        $toEmail = ($HfnuConfig->getValue('admin_email','main') != '') ? $HfnuConfig->getValue('admin_email','main') : $gJConfig->mailer['webmasterEmail'];

        if ($toEmail == '') {
            throw new jException('havefnubb~mail.email.config.not.done.properly');		 
        }
        // send an email only if the forum is installed
        // this avoid to send an email when the forum is installing
        // and send an email to the admin after creating his account
        if ( $HfnuConfig->getValue('installed','main') == 1 ) {
            $user = $event->getParam('user');

            $mail = new jMailer();
            $mail->From       = $gJConfig->mailer['webmasterEmail'];
            $mail->FromName   = $gJConfig->mailer['webmasterName'];
            $mail->Sender     = $gJConfig->mailer['webmasterEmail'];
            $mail->Subject    = jLocale::get('havefnubb~member.registration.new.member.registered');

            $tpl = new jTpl();
            $tpl->assign('login',$user->login);
            $tpl->assign('server',$_SERVER['SERVER_NAME']);
            $mail->Body = $tpl->fetch('havefnubb~warn_new_registration', 'text');

            $mail->AddAddress($toEmail);
            $mail->Send();
        }
   }
   
   function onjcommunity_check_before_save_registration($event) {
	  $user = $event->getParam('user');

	  // check if the user try to register with a banned domain
	  jClasses::inc('havefnubb~bans');
	  // $return is false when the domain of the email is not banned
	  // otherwise ; $return contain the message of the ban
	  $return = bans::checkDomain($user->email);

	  if (is_string($return)) {
		 $event->Add(array('canregister'=>false,'why'=>$return));
	  }
	  else {
		 $event->Add(array('canregister'=>true,'why'=>''));
	  }
	  
   }
}
?>