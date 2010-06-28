<?php
/**
 * @package   havefnubb
 * @subpackage hfnuhardware
 * @author    Laurentj
 * @copyright 2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Listener to answer to Auth and 'Community' events
 */
class authhfnuhardwareListener extends jEventListener{


    // jcommunity_account_show', array('login'=>$user->login, 'user'=>$user,'tpl'=>$tpl));

    function onjcommunity_init_edit_form_account($event) {
        //$login = $event->getParam('login');
        $form =  $event->getParam('form');

        $ctrl= new jFormsControlinput('hw:connection');
        $ctrl->label=jLocale::get('hfnuhardware~hw.connection');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:os');
        $ctrl->label=jLocale::get('hfnuhardware~hw.os');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:proc');
        $ctrl->label=jLocale::get('hfnuhardware~hw.proc');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:ram');
        $ctrl->label=jLocale::get('hfnuhardware~hw.ram');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:display');
        $ctrl->label=jLocale::get('hfnuhardware~hw.display');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:screen');
        $ctrl->label=jLocale::get('hfnuhardware~hw.screen');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:mouse');
        $ctrl->label=jLocale::get('hfnuhardware~hw.mouse');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:keyb');
        $ctrl->label=jLocale::get('hfnuhardware~hw.keyb');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:mb');
        $ctrl->label=jLocale::get('hfnuhardware~hw.mb');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('hw:card');
        $ctrl->label=jLocale::get('hfnuhardware~hw.card');
        $form->addControlBefore($ctrl, 'acc_submit');
    }

    /**
     * prepare the form by initializing fields value
     */
    function onjcommunity_prepare_edit_account ($event) {
        $login = $event->getParam('login');
        $form =  $event->getParam('form');

        $members = jDao::get('jcommunity~user');
        $user = $members->getByLogin($login);

        $fields = jDao::get('havefnubb~member_custom_fields');
        $values = $fields->getByUserAndFamilyType($user->id, 'hw:%');
        foreach($values as $val) {
            $c = $form->getControl($val->type);
            if ($c)
                $c->setData($val->data);
        }
    }

    function onhfbAccountShowTab($event) {
        $event->add(  '<li><a href="#user-profile-hardware">'.jLocale::get('hfnuhardware~hw.hardware').'</a></li>');
    }

    function onhfbAccountShowDiv($event) {
        $tpl = new jTpl();

        $login = $event->getParam('user');

        $members = jDao::get('jcommunity~user');
        $user = $members->getByLogin($login);

        $fields = jDao::get('havefnubb~member_custom_fields');
        $records = $fields->getByUserAndFamilyType($user->id, 'hw:%');
        $hw = array(
                    'connection'=>'',
                    'os'=>'',
                    'proc'=>'',
                    'ram'=>'',
                    'display'=>'',
                    'screen'=>'',
                    'mouse'=>'',
                    'keyb'=>'',
                    'mb'=>'',
                    'card'=>'',
                );
        foreach($records as $val) {
            $hw[substr($val->type, 3)] = $val->data;
        }
        $tpl->assign($hw);

        $event->add($tpl->fetch('hfnuhardware~account_show'));
    }


    /**
     * prepare the display of the form
     */

    function onhfbAccountEditTab($event) {
        $event->add(  '<li><a href="#user-profile-hardware">'.jLocale::get('hfnuhardware~hw.hardware').'</a></li>');
    }

    function onhfbAccountEditInclude($event) {
        $event->add('hfnuhardware~account_edit');
    }

/*    function onjcommunity_edit_account ($event) {
        $login = $event->getParam('login');
        $form =  $event->getParam('form');
        $response = $event->getParam('rep');
        $tpl = $event->getParam('tpl');
    }*/

    // jcommunity_check_before_save_account', array('login'=>$user,'form'=>$form));

    /**
    * to answer to jcommunity_save_account event
    * @param object $event the given event to answer to
    */
    function onjcommunity_save_account ($event) {
        $login = $event->getParam('login');
        $form =  $event->getParam('form');
        $user = $event->getParam('record');
        $to_insert = $event->getParam('to_insert');
        if ($to_insert)
            return; // we need an existing user

        $fields = jDao::get('havefnubb~member_custom_fields');
        $id = $user->id;
        $record = jDao::createRecord('havefnubb~member_custom_fields');
        $record->id = $id;

        $imlist = array(
            'hw:connection', 'hw:os', 'hw:proc',
            'hw:ram', 'hw:display', 'hw:screen',
            'hw:mouse', 'hw:keyb', 'hw:mb', 'hw:card',
        );

        foreach ($imlist as $im) {
            $imrec = $fields->get($id, $im);
            $data = $form->getData($im);
            if ($imrec) {
                if ($data != $imrec->data) {
                    if ($data == '')
                        $fields->delete($id, $im);
                    else{
                        $imrec->data = $data;
                        $fields->update($imrec);
                    }
                }
            }
            else {
                if ($data != '') {
                    $record->type = $im;
                    $record->data = $data;
                    $fields->insert($record);
                }
            }
        }

	}

}
