<?php
/**
* @package   havefnubb
* @subpackage modulesinfo
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class modulesinfoModuleInstaller extends jInstallerModule {

    function install() {

        if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('modulesinfo.access', 'modulesinfo~modulesinfo.acl.access');
            jAcl2DbManager::addRight('admins', 'modulesinfo.access'); // for admin group
        }
        
    }
}