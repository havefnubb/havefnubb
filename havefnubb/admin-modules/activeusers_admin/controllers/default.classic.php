<?php
/**
* @package   havefnubb
* @subpackage activeusers_admin
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {

    public $pluginParams = array(
        '*' => array('auth.required'=>true,
                     'jacl2.right'=>'activeusers.configuration'
        ),
    );

    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        $form = jForms::get('config');
        if(!$form) {
            $form = jForms::create('config');
            $activeusers = jClasses::create('activeusers~connectedusers');
            $form->setData('timeout_visit', $activeusers->getVisitTimeout());
        }

        $tpl = new jTpl();
        $tpl->assign('form',$form);

        $rep->body->assign('MAIN', $tpl->fetch('config'));

        return $rep;
    }
    
    function save() {
        $rep = $this->getResponse('redirect');
        $rep->action = 'default:index';
        
        $form = jForms::fill('config');
        if (!$form)
            return $rep;
        if (!$form->check()) {
            return $rep;
        }
        $activeusers = jClasses::create('activeusers~connectedusers');

        try {
            $activeusers->saveVisitTimeout($form->getData('timeout_visit'));
            jMessage::add(jLocale::get('main.config.save.ok'));
        }catch(Exception $e) {
            jMessage::add('Error: '.$e->getMessage(), 'error');
        }
        return $rep;
    }
}

