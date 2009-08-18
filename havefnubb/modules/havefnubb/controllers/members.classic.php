<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class membersCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(

        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),		
		'index' => array('history.add'=>true,
						 'history.label'=>'Accueil',
						 'history.title'=>'Aller vers la page d\'accueil')
    );
    
    function index() {
		global $HfnuConfig;
        $title = stripslashes($HfnuConfig->getValue('title','main'));
        $rep = $this->getResponse('html');
		
		$letter = $this->param('letter');
		$id_rank = (int) $this->param('id_rank');
        
        $memberSearch = (string) $this->param('member_search');
		
		$page = 0;		
		$page = (int) $this->param('page');
		
		// get the group name of the group id we request
		$grpid = (int) $this->param('grpid');
		$groupname = jLocale::get('havefnubb~member.memberlist.allgroups');
		if ($grpid > 0 ) {
			$dao = jDao::get('jelix~jacl2group');
			$conditions = jDao::createConditions();
			$conditions->addCondition('id_aclgrp','=',$grpid);
			$grpnames = $dao->findBy($conditions);
			foreach ($grpnames as $grpname)
				$groupname = $grpname->name;
		}
		$beginningBy = '';
		if ($letter != '')
			$beginningBy = ' - ' .jLocale::get('havefnubb~member.memberlist.members.beginning.by',array($letter));
		// change the label of the breadcrumb
        if ($page == 0) {		
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list'));
			$rep->title = jLocale::get('havefnubb~member.memberlist.members.list') . ' - ' . $groupname . $beginningBy;
		}
		else {
			$GLOBALS['gJCoord']->getPlugin('history')->change('label', jLocale::get('havefnubb~member.memberlist.members.list') . ' ' .($page+1));
			$rep->title = jLocale::get('havefnubb~member.members.list') . ' - ' . $groupname .$beginningBy. ' ' .($page+1) ;			
		}
		
        $rep->body->assignZone('MAIN',
							   'memberlist',array('page'=>$page,
									'grpid'=>$grpid,
									'letter'=>$letter,
									'memberSearch'=>$memberSearch));
        return $rep;
    }
	
	// The user want to change his password
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

	// let's change the user password
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


	function mail() {
		$rep = $this->getResponse('html');
		$rep->body->assign('selectedMenuItem','members');
		$rep->body->assignZone('MAIN', 'jmessenger~links');
		return $rep;
	}	

}
