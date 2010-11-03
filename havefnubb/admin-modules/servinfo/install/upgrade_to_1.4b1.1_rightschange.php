<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class servinfoModuleUpgrader_rightschange extends jInstallerModule {

    function postInstall() {
        if ($this->firstExec('acl2')) {
            jAcl2DbManager::removeSubject('hfnu.admin.server.info');
            jAcl2DbManager::addSubject('servinfo.access', 'servinfo~servinfo.acl.access');
            jAcl2DbManager::addRight(1, 'servinfo.access'); // for admin group
        }
    }
}