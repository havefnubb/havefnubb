<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class servinfoModuleInstaller extends jInstallerModule {

    function postInstall() {
        if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('servinfo.access', 'servinfo~servinfo.acl.access');
            jAcl2DbManager::addRight('admins', 'servinfo.access'); // for admin group
        }
    }
}