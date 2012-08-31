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
        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile')) {
            $this->copyFile('../plugins/coord/activeusers/activeusers.coord.ini.php.dist',
                            'config:activeusers.coord.ini.php');
            $this->copyFile('botsagent.ini.php',
                            'config:botsagent.ini.php');
        }

        /*if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('my.subject', 'activeusers~acl.my.subject');
            jAcl2DbManager::addRight('admins', 'my.subject'); // for admin group
        }
        */
    }
}
