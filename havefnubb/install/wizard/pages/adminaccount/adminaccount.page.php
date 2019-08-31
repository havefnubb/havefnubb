<?php
/**
 * @package     InstallWizard
 * @subpackage  pages
 * @author      Laurent Jouanneau
 * @copyright   2010 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the pages for Installation wizard
 */
class adminaccountWizPage extends installWizardPage {

    /**
     * action to display the page
     * @param jTpl $tpl the template container
     * @return true
     */
    function show ($tpl) {
        include (dirname(__FILE__).'/../../../version.php');        
        if (!isset($_SESSION['adminaccount'])) {
            $_SESSION['adminaccount'] = array(
                'login'=>'',
                'password'=>'',
                'password_confirm'=>'',
                'email'=>'',
                'alreadyInstalled'=>$alreadyInstalled,
                'errors'=>array()
            );
        }

        $tpl->assign($_SESSION['adminaccount']);

        return true;
    }

    /**
     * action to process the page after the submit
     * @return 0
     */
    function process() {

        $errors = array();
        $login = $_SESSION['adminaccount']['login'] = trim($_POST['login']);
        if ($login == '') {
            $errors[] = $this->locales['error.missing.login'];
        }

        $password = $_SESSION['adminaccount']['password'] = trim($_POST['password']);
        if ($password == '') {
            $errors[] = $this->locales['error.missing.password'];
        }

        $passwordconf = $_SESSION['adminaccount']['password_confirm'] = trim($_POST['password_confirm']);
        if ($password != $passwordconf) {
            $errors[] = $this->locales['error.confirm.password'];
        }

        $email = $_SESSION['adminaccount']['email'] = trim($_POST['email']);
        if ($email == '') {
            $errors[] = $this->locales['error.missing.email'];
        }

        if (count($errors)) {
            $_SESSION['adminaccount']['errors'] = $errors;
            return false;
        }

        jApp::loadConfig('havefnubb/config.ini.php');
        
        $db = jDb::getConnection();
        $db->exec('INSERT INTO '.$db->encloseName($db->prefixTable('community_users')).
                  ' (login, password, email, nickname, status, create_date) VALUES ('.
                  $db->quote($login).','.$db->quote(md5($password)).','.
                  $db->quote($email).','.$db->quote($login).',1,'.
                  "'".date('Y-m-d H:i:s')."')");
        $idu = $db->lastInsertId();

        $db->exec('INSERT INTO '.$db->encloseName($db->prefixTable('jacl2_group')).' (id_aclgrp, name, grouptype, ownerlogin) '.
                  'VALUES ('.$db->quote('__priv_'.$login).','.$db->quote($login).',2,'.$db->quote($login).')');

        $db->exec('INSERT INTO '.$db->encloseName($db->prefixTable('jacl2_user_group')).' (login, id_aclgrp) VALUES ('.$db->quote($login).',\'admins\')');
        $db->exec('INSERT INTO '.$db->encloseName($db->prefixTable('jacl2_user_group')).' (login, id_aclgrp) VALUES ('.$db->quote($login).','.$db->quote('__priv_'.$login).')');

        unset($_SESSION['adminaccount']);
        return 0;
    }

}
