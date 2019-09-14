<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010-2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

class hfnusearchModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function install(InstallHelpers $helpers)
    {
        $helpers->database()->execSQLScript('sql/install');
    }

    public function postInstall(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('hfnu.search', 'havefnubb~acl2.search');
        jAcl2DbManager::addRole('hfnu.admin.search', 'havefnubb~acl2.admin.search');


        jAcl2DbManager::addRight('admins', 'hfnu.search');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.search');
        jAcl2DbManager::addRight('users', 'hfnu.search');
        jAcl2DbManager::addRight('users', 'hfnu.admin.search');
        jAcl2DbManager::addRight('moderators', 'hfnu.search');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.search');
        jAcl2DbManager::addRight('__anonymous', 'hfnu.search');
        jAcl2DbManager::addRight('__anonymous', 'hfnu.admin.search');

    }
}
