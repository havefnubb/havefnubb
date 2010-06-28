<?php
/**
 * @package   havefnubb
 * @subpackage hfnuim
 * @author    Laurentj
 * @copyright 2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Listener to answer to Auth and 'Community' events
 */
class authhfnuimListener extends jEventListener{


    // jcommunity_account_show', array('login'=>$user->login, 'user'=>$user,'tpl'=>$tpl));

    /**
     * init the form by adding the control we need
     */
    function onjcommunity_init_edit_form_account($event) {
        //$login = $event->getParam('login');
        $form =  $event->getParam('form');

        $ctrl= new jFormsControlinput('im:xfire');
        $ctrl->label=jLocale::get('hfnuim~im.xfire');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:icq');
        $ctrl->label=jLocale::get('hfnuim~im.icq');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:yim');
        $ctrl->datatype= new jDatatypeemail();
        $ctrl->label=jLocale::get('hfnuim~im.yim');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:hotmail');
        $ctrl->datatype= new jDatatypeemail();
        $ctrl->label=jLocale::get('hfnuim~im.hotmail');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:aol');
        $ctrl->datatype= new jDatatypeemail();
        $ctrl->label=jLocale::get('hfnuim~im.aol');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:gtalk');
        $ctrl->datatype= new jDatatypeemail();
        $ctrl->label=jLocale::get('hfnuim~im.gtalk');
        $form->addControlBefore($ctrl, 'acc_submit');

        $ctrl= new jFormsControlinput('im:jabber');
        $ctrl->datatype= new jDatatypeemail();
        $ctrl->label=jLocale::get('hfnuim~im.jabber');
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
        $values = $fields->getByUserAndFamilyType($user->id, 'im:%');
        foreach($values as $val) {
            $c = $form->getControl($val->type);
            if ($c)
                $c->setData($val->data);
        }
    }

    function onhfbAccountShowTab($event) {
        $event->add(  '<li><a href="#user-profile-messenger">'.jLocale::get('hfnuim~im.instant.messenger').'</a></li>');
    }

    function onhfbAccountShowDiv($event) {
        $tpl = new jTpl();

        $login = $event->getParam('user');

        $members = jDao::get('jcommunity~user');
        $user = $members->getByLogin($login);

        $fields = jDao::get('havefnubb~member_custom_fields');
        $records = $fields->getByUserAndFamilyType($user->id, 'im:%');
        $im = array(
                    'xfire'=>'',
                    'icq'=>'',
                    'yim'=>'',
                    'hotmail'=>'',
                    'aol'=>'',
                    'gtalk'=>'',
                    'jabber'=>'',
                );
        foreach($records as $val) {
            $im[substr($val->type, 3)] = $val->data;
        }
        $tpl->assign($im);

        $event->add($tpl->fetch('hfnuim~account_show'));
    }

    /**
     * prepare the display of the form
     */

    function onhfbAccountEditTab($event) {
        $event->add(  '<li><a href="#user-profile-messenger">'.jLocale::get('hfnuim~im.instant.messenger').'</a></li>');
    }

    function onhfbAccountEditInclude($event) {
        $event->add('hfnuim~account_edit');
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
            'im:xfire', 'im:icq', 'im:yim', 'im:hotmail',
            'im:aol', 'im:gtalk', 'im:jabber',
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
