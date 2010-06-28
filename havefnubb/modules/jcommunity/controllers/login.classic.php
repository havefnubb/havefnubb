<?php
/**
* @package     jelix-modules
* @subpackage  jauth
* @author      Laurent Jouanneau
* @contributor Antoine Detante
* @copyright   2005-2008 Laurent Jouanneau, 2007 Antoine Detante
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class loginCtrl extends jController {

    public $pluginParams = array(
      '*'=>array('auth.required'=>false)
    );

    function index() {
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get('login.login.title');
        $rep->body->assignZone('MAIN','jcommunity~login', array('as_main_content'=>true));
        return $rep;
    }

    /**
    *
    */
    function in() {
        $rep = $this->getResponse('redirectUrl');
        $conf = $GLOBALS['gJCoord']->getPlugin('auth')->config;
        $url_return = '/';

        if ($conf['after_login'] == '')
            throw new jException ('jcommunity~login.error.no.auth_login');

        if ($conf['after_logout'] == '')
            throw new jException ('jcommunity~login.error.no.auth_logout');

        $form = jForms::fill('jcommunity~login');
        if(!$form) {
            $rep->url = jUrl::get($conf['after_logout']);
            return $rep;
        }

        if (!jAuth::login($form->getData('auth_login'), $form->getData('auth_password'), $form->getData('auth_remember_me'))){
            sleep (intval($conf['on_error_sleep']));
            $form->setErrorOn('auth_login',jLocale::get('jcommunity~login.error'));
            //jMessage::add(jLocale::get('jcommunity~login.error'), 'error');
            if ($auth_url_return = $this->param('auth_url_return'))
                $url_return = jUrl::get('login:index', array('auth_url_return'=>$auth_url_return));
            else
                $url_return = jUrl::get('login:index');
        } else {
            jForms::destroy('jcommunity~login');
            if (!($conf['enable_after_login_override'] && $url_return = $this->param('auth_url_return'))){
                $url_return =  jUrl::get($conf['after_login']);
            }
        }

        $rep->url = $url_return;
        return $rep;
    }

    /**
    *
    */
    function out() {
        $rep = $this->getResponse('redirectUrl');
        jAuth::logout();
        $conf = $GLOBALS['gJCoord']->getPlugin ('auth')->config;

        if ($conf['after_logout'] == '')
            throw new jException ('jcommunity~login.error.no.auth_logout');

        if (!($conf['enable_after_logout_override'] && $url_return= $this->param('auth_url_return'))){
            $url_return =  jUrl::get($conf['after_logout']);
        }

        $rep->url = $url_return;
        return $rep;
    }
}
