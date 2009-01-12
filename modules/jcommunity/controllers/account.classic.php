<?php
/**
* @package      jcommunity
* @subpackage   
* @author       Laurent Jouanneau <laurent@xulfr.org>
* @contributor
* @copyright    2008 Laurent Jouanneau
* @link         http://jelix.org
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

include(dirname(__FILE__).'/../classes/defines.php');

class accountCtrl extends jController {

    public $pluginParams = array(
      '*'=>array('auth.required'=>true),
      'show'=>array('auth.required'=>false)
    );

    /**
    * show informations about a user
    */
    function show() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('username',$this->param('user'));

        $users = jDao::get('jcommunity~user');

        $user = $users->getByLogin($this->param('user'));
        if(!$user || $user->status < JCOMMUNITY_STATUS_VALID) {
            $rep->body->assign('MAIN',$tpl->fetch('account_unknow'));
            return $rep;
        }

        $tpl->assign('user',$user);
        $tpl->assign('himself', (jAuth::isConnected() && jAuth::getUserSession()->login == $user->login));
        $rep->body->assign('MAIN',$tpl->fetch('account_show'));
        return $rep;
    }

    function prepareEdit() {
        $user = $this->param('user');
        $rep = $this->getResponse('redirect');
        $rep->action = 'jcommunity~account:show';
        $rep->params=array('user'=>$user);

        if(!jAuth::isConnected() || jAuth::getUserSession()->login != $user) {
            return $rep;
        }

        $form = jForms::create('account', $this->param('user'));

        try {
            $form->initFromDao('user');
        }catch(Exception $e){
            return $rep;
        }

        jEvent::notify('jcommunity_prepare_edit_account', array('form'=>$form));

        $rep->action = 'jcommunity~account:edit';
        return $rep;
    }

    function edit() {
        $user = $this->param('user');
        if($user == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $user) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'jcommunity~account:show';
            $rep->params=array('user'=>$user);
            return $rep;
        }

        $form = jForms::get('account', $user);
        if(!$form) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'jcommunity~account:show';
            $rep->params=array('user'=>$user);
            return $rep;
        }

        $rep = $this->getResponse('html');

        $tpl = new jTpl();
        $tpl->assign('username', $user);
        $tpl->assign('form', $form);

        jEvent::notify('jcommunity_edit_account', array('rep'=>$rep, 'form'=>$form, 'tpl'=>$tpl));

        $rep->body->assign('MAIN', $tpl->fetch('account_edit'));

        return $rep;
    }

    function save() {
        $user = $this->param('user');

        $rep = $this->getResponse('redirect');
        $rep->action = 'jcommunity~account:show';
        $rep->params=array('user'=>$user);

        if($user == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $user) {
            return $rep;
        }

        $form = jForms::fill('account', $user);
        if(!$form) {
            return $rep;
        }

        $form->check();
        $accountFact = jDao::get('user');
        if($accountFact->verifyNickname($user, $form->getData('nickname'))){
            $form->setErrorOn('nickname', jLocale::get('account.error.dup.nickname'));
        }

        jEvent::notify('jcommunity_check_before_save_account', array('form'=>$form));
        if(count($form->getErrors())){
            $rep->action = 'jcommunity~account:edit';
        } else {
            jEvent::notify('jcommunity_save_account', array('form'=>$form));
            $form->saveToDao('user');
        }

        return $rep;
    }


    function destroy() {
        $user = $this->param('user');
        if($user == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $user) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'jcommunity~account:show';
            $rep->params=array('user'=>$user);
            return $rep;
        }
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('username',$user);
        $rep->body->assign('MAIN', $tpl->fetch('account_destroy'));
        return $rep;
    }


    function dodestroy() {
        $user = $this->param('user');
        $rep = $this->getResponse('redirect');
        $rep->action = 'jcommunity~account:show';
        $rep->params=array('user'=>$user);

        if($user == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $user) {
            return $rep;
        }

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('username',$user);

        if(jAuth::removeUser($user)) {
            jAuth::logout();
            $rep->body->assign('MAIN', $tpl->fetch('account_destroy_done'));
        } else {
            $rep->body->assign('MAIN', $tpl->fetch('account_destroy_cancel'));
        }

        return $rep;
    }

}
