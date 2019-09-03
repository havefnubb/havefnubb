<?php
/**
* @package   havefnubb
* @subpackage modulesinfo
* @author    Laurent Jouanneau
* @copyright 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

class modulesinfoModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function postInstall(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('modulesinfo.access', 'modulesinfo~modulesinfo.acl.access');
        jAcl2DbManager::addRight('admins', 'modulesinfo.access'); // for admin group
    }
}