<?php
/**
* @package   havefnubb
* @subpackage activeusers_admin
* @author    Laurent Jouanneau
* @copyright 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/


use Jelix\Installer\Module\API\InstallHelpers;

class activeusers_adminModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function postInstall(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('activeusers.configuration', 'activeusers_admin~main.acl.subject');
        jAcl2DbManager::addRight('admins', 'activeusers.configuration'); // for admin group
    }
}