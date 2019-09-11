<?php
/**
* @package   havefnubb
* @subpackage activeusers
* @author    Laurent Jouanneau
* @copyright 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

class activeusersModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function install(InstallHelpers $helpers)
    {
        $helpers->database()->execSQLScript('sql/install');

        /*if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('my.subject', 'activeusers~acl.my.subject');
            jAcl2DbManager::addRight('admins', 'my.subject'); // for admin group
        }
        */
    }
}
