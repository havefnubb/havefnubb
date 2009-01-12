<?php
/**
* @package      jcommunity
* @subpackage
* @author       Laurent Jouanneau <laurent@jelix.org>
* @contributor
* @copyright    2007-2008 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

include(dirname(__FILE__).'/../classes/defines.php');

class registrationCtrl extends jController {

    public $pluginParams = array(
      '*'=>array('auth.required'=>false)
    );

    /**
    * registration form
    */
    function index() {
        if(jAuth::isConnected())
            return $this->noaccess();

        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','registration');
        return $rep;
    }

    /**
    * save new user and send an email for a confirmation, with
    * a key to activate the account
    */
    function save() {
        if(jAuth::isConnected())
            return $this->noaccess();

        global $gJConfig;
        $rep= $this->getResponse("redirect");
        $rep->action="registration:index";

        $form = jForms::fill('registration');
        if(!$form->check()){
            return $rep;
        }

        $login = $form->getData('reg_login');
        if(jAuth::getUser($login)){
            $form->setErrorOn('reg_login',jLocale::get('register.form.login.exists'));
            return $rep;
        }

        $pass = jAuth::getRandomPassword(8);
        $key = substr(md5($login.'-'.$pass),1,10);

        $user = jAuth::createUserObject($login,$pass);
        $user->email = $form->getData('reg_email');
        $user->nickname = $login;
        $user->status = JCOMMUNITY_STATUS_NEW;
        $user->request_date = date('Y-m-d H:i:s');
        $user->keyactivate = $key;
        jAuth::saveNewUser($user);

        $mail = new jMailer();
        $mail->From = $gJConfig->mailer['webmasterEmail'];
        $mail->FromName = $gJConfig->mailer['webmasterName'];
        $mail->Sender = $gJConfig->mailer['webmasterEmail'];
        $mail->Subject = jLocale::get('register.mail.new.subject');

        $tpl = new jTpl();
        $tpl->assign(compact('login','pass','key'));
        $tpl->assign('server',$_SERVER['SERVER_NAME']);
        $mail->Body = $tpl->fetch('mail_registration', 'text');

        $mail->AddAddress($user->email);
        //$mail->SMTPDebug = true;
        $mail->Send();

        jForms::destroy('registration');

        $rep->action="registration:confirmform";
        return $rep;
    }

    /**
    * form to enter the confirmation key
    * to activate the account
    */
    function confirmform() {
        if(jAuth::isConnected())
            return $this->noaccess();

        $rep = $this->getResponse('html');
        $form = jForms::get('confirmation');
        if($form == null){
            $form = jForms::create('confirmation');
        }
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN',$tpl->fetch('registration_confirmation'));
        return $rep;
    }

    /**
    * activate an account. the key should be given as a parameter
    */
    function confirm() {
        if(jAuth::isConnected())
            return $this->noaccess();

        $rep= $this->getResponse("redirect");
        $rep->action="registration:confirmform";

        if($_SERVER['REQUEST_METHOD'] != 'POST')
            return $rep;

        $form = jForms::fill('confirmation');
        if ($form == null) {
            return $rep;
        }

        if (!$form->check()) {
            return $rep;
        }

        $login = $form->getData('conf_login');
        $user = jAuth::getUser($login);
        if (!$user) {
            $form->setErrorOn('conf_login',jLocale::get('register.form.confirm.login.doesnt.exist'));
            return $rep;
        }

        if ($user->status != JCOMMUNITY_STATUS_NEW) {
            jForms::destroy('confirmation');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $tpl->assign('already',true);
            $rep->body->assign('MAIN',$tpl->fetch('registration_ok'));
            return $rep;
        }

        if ($form->getData('conf_key') != $user->keyactivate) {
            $form->setErrorOn('conf_key',jLocale::get('register.form.confirm.bad.key'));
            return $rep;
        }

        $user->status = JCOMMUNITY_STATUS_VALID;
        jAuth::updateUser($user);
        jAuth::changePassword($login, $form->getData('conf_password'));
        jAuth::login($login, $form->getData('conf_password'));
        jForms::destroy('confirmation');
        $rep->action="registration:confirmok";
        return $rep;
    }

    /**
    * Page which confirm that the account is activated
    */
    function confirmok() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('already',false);
        $rep->body->assign('MAIN',$tpl->fetch('registration_ok'));
        return $rep;
    }

    protected function noaccess() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $rep->body->assign('MAIN',$tpl->fetch('no_access'));
        return $rep;
    }
}
