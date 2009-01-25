<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    function config() {
        global $HfnuConfig ;        
        $rep = $this->getResponse('html');
		$submit = $this->param('validate');
        
		if ($submit == jLocale::get('hfnuadmin~config.saveBt') ) {
            
            $form = jForms::get('hfnuadmin~config');
            
            if (!$form->check()) {
                $rep = $this->getResponse('redirect');
                $rep->action='havefnubb~default:index';
                return $rep;
            }
            
            $HfnuConfig->setValue('title',htmlentities($this->param('title')));
            $HfnuConfig->setValue('description',htmlentities($this->param('description')));
            $HfnuConfig->setValue('theme',htmlentities($this->param('theme')));
            $HfnuConfig->setValue('rules',htmlentities($this->param('rules')));
            $HfnuConfig->setValue('webmaster_email',htmlentities($this->param('webmaster_email')));
            $HfnuConfig->setValue('admin_email',htmlentities($this->param('admin_email')));
            
            $HfnuConfig->setValue('posts_per_page',htmlentities($this->param('posts_per_page')));
            $HfnuConfig->setValue('replies_per_page',htmlentities($this->param('replies_per_page')));
            $HfnuConfig->setValue('members_per_page',htmlentities($this->param('members_per_page')));
            $HfnuConfig->setValue('stats_nb_of_lastpost',htmlentities($this->param('stats_nb_of_lastpost')));
            
            $HfnuConfig->save();
			jForms::destroy('hfnuadmin~config');
			$rep->action ='hfnuadmin~default:config';
			return $rep;            
        }
        else 
            $form = jForms::create('hfnuadmin~config');

        $form->setData('title',           stripslashes($HfnuConfig->getValue('title')));
        $form->setData('description',     stripslashes($HfnuConfig->getValue('description')));
        $form->setData('theme',           stripslashes($HfnuConfig->getValue('theme')));
        $form->setData('rules',           stripslashes($HfnuConfig->getValue('rules')));
        $form->setData('webmaster_email', stripslashes($HfnuConfig->getValue('webmaster_email')));
        $form->setData('admin_email',     stripslashes($HfnuConfig->getValue('admin_email')));
        $form->setData('posts_per_page',  (int) $HfnuConfig->getValue('posts_per_page'));
        $form->setData('replies_per_page',(int) $HfnuConfig->getValue('replies_per_page'));
        $form->setData('members_per_page',(int) $HfnuConfig->getValue('members_per_page'));
        $form->setData('stats_nb_of_lastpost',(int) $HfnuConfig->getValue('stats_nb_of_lastpost'));

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $rep->body->assign('MAIN',$tpl->fetch('config'));
        return $rep;
    }
    
    function categories() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
    function forums() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
    function reporting() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
    function ranks() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    
    }    
    function ban() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
}

