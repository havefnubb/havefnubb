<?php
/**
* @package   havefnubb
* @subpackage activeusers_admin
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class activeusers_adminModuleInstaller extends jInstallerModule {

    function install() {
        //if ($this->firstDbExec())
        //    $this->execSQLScript('sql/install');

        if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('activeusers.configuration', 'activeusers_admin~main.acl.subject');
            jAcl2DbManager::addRight(1, 'activeusers.configuration'); // for admin group
        }
    }
}