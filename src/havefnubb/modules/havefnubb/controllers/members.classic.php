<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage any specific members events
*/
class membersCtrl extends jController {
    /**
     * @var $pluginParams plugins to manage the behavior of the controller
     */
    public $pluginParams = array (
        '*' => array('auth.required'=>true,
            'banuser.check'=>true,
        ),
        'index' => array('history.add'=>true,
            'history.label'=>'Accueil',
            'history.title'=>'Aller vers la page d\'accueil')
    );
    /**
    * handle the search of specific member
    */
    function index() {
        $title = stripslashes(jApp::config()->havefnubb['title']);
        $rep = $this->getResponse('html');

        $letter = $this->param('letter');
        $id_rank = (int) $this->param('id_rank');

        $memberSearch = (string) $this->param('member_search');

        $page = 0;
        $page = (int) $this->param('page');

    // get the group name of the group id we request
        $grpid = $this->param('grpid');
        $groupname = jLocale::get('havefnubb~member.memberlist.allgroups');
        if ($grpid != '__anonymous' ) {
            $dao = jDao::get('jacl2db~jacl2group');
            $grpname = $dao->get($grpid);
            $groupname = $grpname->name;
        }
        $beginningBy = '';

        if (strlen($letter) == 1 )
            $beginningBy = ' - ' .jLocale::get('havefnubb~member.memberlist.members.beginning.by',array($letter));
        // change the label of the breadcrumb
            if ($page == 0) {
            jApp::coord()->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list'));
            $rep->title = jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname . $beginningBy;
        }
        else {
            jApp::coord()->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list') . ' ' .($page+1));
            $rep->title = jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname .$beginningBy. ' ' .($page+1) ;
        }

        $rep->body->assignZone('MAIN',
            'memberlist',array('page'=>$page,
                 'grpid'=>$grpid,
                 'letter'=>$letter,
                 'memberSearch'=>$memberSearch));
        return $rep;
    }

    /**
     * The user want to change his password
     */
    function changepwd() {
        $login = $this->param('user');
        if($login == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $login) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'jcommunity~account:show';
            $rep->params=array('user'=>$user);
            return $rep;
        }

        $form = jForms::create('havefnubb~pwd', $login);
        $form->initFromDao('havefnubb~pwd');
        $rep = $this->getResponse('html');

        $tpl = new jTpl();
        $tpl->assign('login', $login);
        $tpl->assign('form', $form);

        $rep->body->assign('selectedMenuItem','members');
        $rep->body->assign('MAIN', $tpl->fetch('member_changepwd'));

        return $rep;
    }
    /**
    * let's change the user password
    */
    function savenewpwd() {
        $login = $this->param('user');

        $rep = $this->getResponse('redirect');
        $rep->action = 'jcommunity~account:show';
        $rep->params=array('user'=>$login);

        if($login == '' || !jAuth::isConnected() || jAuth::getUserSession()->login != $login) {
            return $rep;
        }

        $form = jForms::fill('havefnubb~pwd', $login);
        if(!$form) {
            return $rep;
        }

    // check the form !
        $form->check();
        //if error go back to the form to retry to change the password
        if(count($form->getErrors())){
            $rep->action = 'havefnubb~members:changepwd';

        // check if the new password is different from the actual one
        } else {
            if ($form->getData('conf_password') == $form->getData('old_password')) {
                jMessage::add(jLocale::get('havefnubb~members.pwd.passwd.are.the.same.unchanged'),'warning');
                $rep->action = 'havefnubb~members:changepwd';
                return $rep;
            }
            //update the password
            $passwd = $form->getData('conf_password');
            $user = jAuth::getUser($login);
            // update the user info
            jAuth::updateUser($user);
            // change the pass
            jAuth::changePassword($login, $passwd);
            // login back with new pass
            jAuth::login($login, $passwd);
            jForms::destroy('havefnubb~pwd');
        }
        jMessage::add(jLocale::get('havefnubb~member.pwd.passwd.successfully.changed'),'ok');
        return $rep;
    }
    /**
     * call of internal messaging
     */
    function mail() {
        $rep = $this->getResponse('html');
        $rep->body->assign('selectedMenuItem','members');
        $rep->body->assignZone('MAIN', 'jmessenger~links');
        return $rep;
    }
}
