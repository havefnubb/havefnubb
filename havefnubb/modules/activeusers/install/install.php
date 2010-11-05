<?php
/**
* @package   havefnubb
* @subpackage activeusers
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class activeusersModuleInstaller extends jInstallerModule {

    function install() {
        if ($this->firstDbExec())
            $this->execSQLScript('sql/install');

        /*if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('my.subject', 'activeusers~acl.my.subject');
            jAcl2DbManager::addRight(1, 'my.subject'); // for admin group
        }
        */
    }
}