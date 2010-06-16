<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * Listener to answer to Auth and 'Community' events
 */
class authhavefnubbListener extends jEventListener{

    /**
    * to answer to AuthLogin event
    * @param object $event the given event to answer to
    */
    function onAuthLogin ($event) {
        global $gJConfig;
        $login = $event->getParam('login');

        // update the last connection access
        $dao = jDao::get('havefnubb~member');

        $user = $dao->getByLogin($login);
        if (!$user) {
          throw new jException('havefnubb~mail.email.config.not.done.properly');
        }
        // put the current date
        $user->member_last_connect = $user->connected;
        $user->connected = time();

        $dao->update($user);

        $_SESSION['JX_LANG'] = $user->member_language;
        $gJConfig->locale = $user->member_language;
    }

    /**
    * to answer to AuthLogout event
    * @param object $event the given event to answer to
    */
    function onAuthLogout($event) {
        $_SESSION['JX_LANG'] = '';
        unset($_SESSION['JX_LANG']);
    }

    function onAuthRemoveUser($event) {
        $dao = jDao::get('havefnubb~member_custom_fields');
        $dao->deleteByUser($event->getParam('user')->id);
    }

    /**
    * to answer to jcommunity_save_account event
    * @param object $event the given event to answer to
    */
    function onjcommunity_save_account ($event) {
        global $gJConfig;
        $form = $event->getParam('form');
        $form->check();
        if ( $form->getData('member_language') != '') {
            $_SESSION['JX_LANG'] = $form->getData('member_language');
            $gJConfig->locale = $form->getData('member_language');
        }
        $ext = '';
        $id = jAuth::getUserSession()->id;
        if ($form->getData('member_avatar') != '' ) {
            $max_width = $gJConfig->havefnubb['avatar_max_width'];
            $max_height = $gJConfig->havefnubb['avatar_max_height'];
            @unlink (JELIX_APP_WWW_PATH.'images/avatars/'.$id.'.png');
            @unlink (JELIX_APP_WWW_PATH.'images/avatars/'.$id.'.jpg');
            @unlink (JELIX_APP_WWW_PATH.'images/avatars/'.$id.'.jpeg');
            @unlink (JELIX_APP_WWW_PATH.'images/avatars/'.$id.'.gif');

            $avatar = $form->getData('member_avatar');

            if (strpos($avatar,'.png') > 0 )
                $ext = '.png';
            elseif (strpos($avatar,'.jpg') > 0 )
                $ext = '.jpg';
            elseif (strpos($avatar,'.jpeg') > 0 )
                $ext = '.jpeg';
            elseif (strpos($avatar,'.gif') > 0 )
                $ext = '.gif';

            $form->saveFile('member_avatar', JELIX_APP_WWW_PATH.'hfnu/images/avatars/', $id.$ext);

            list($width, $height) = getimagesize(JELIX_APP_WWW_PATH.'hfnu/images/avatars/'.$id.$ext);
            if (empty($width) || empty($height) || $width > $max_width || $height > $max_height) {
                @unlink (JELIX_APP_WWW_PATH.'images/avatars/'.$id.$ext);
                jMessage::add(
                     jLocale::get('havefnubb~member.profile.avatar.too.wide',array($max_width.' x '. $max_height))
                     ,'error');
                return;
            }

        }
        jMessage::add(jLocale::get('havefnubb~member.profile.updated'),'ok');
    }

    /**
     * to answer to AuthNewUser event
     * @param object $event the given event to answer to
     */
    function onAuthNewUser ($event) {
        global $gJConfig;

        $toEmail = ($gJConfig->havefnubb['admin_email'] != '') ? $gJConfig->havefnubb['admin_email'] : $gJConfig->mailer['webmasterEmail'];

        if ($toEmail == '') {
            throw new jException('havefnubb~mail.email.config.not.done.properly');
        }
        // send an email only if the forum is installed
        // this avoid to send an email when the forum is installing
        // and send an email to the admin after creating his account
        if ( $gJConfig->havefnubb['installed'] == 1 ) {
            $user = $event->getParam('user');
            // update the creation date
            $dao = jDao::get('havefnubb~member');

            $user = $dao->getByLogin($user->login);
            if (!$user) {
                throw new jException('havefnubb~member.member.does.not.exist');
            }
            // put the current date
            $user->member_created = date('Y-m-d H:i:s');
            $dao->update($user);

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
    /**
     * to answer to jcommunity_registration_prepare_save event
     * @param object $event the given event to answer to
     */
    function onjcommunity_registration_prepare_save($event) {
        $user = $event->getParam('user');

        // check if the user try to register with a banned domain
        jClasses::inc('havefnubb~bans');
        // $return is false when the domain of the email is not banned
        // otherwise ; $return contain the message of the ban
        $return = bans::checkDomain($user->email);

        if (is_string($return)) {
            $event->Add(array('errorRegistration'=>$return));
        }
        else {
            $event->Add(array('errorRegistration'=>''));
        }
    }
}
