<?php
/**
* @package   havefnubb
* @subpackage jelixcache
* @author    Laurent Jouanneau
* @copyright 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

class jelixcacheModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function postInstall(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('jelixcache.access', 'jelixcache~jelixcache.acl.access');
        jAcl2DbManager::addRight('admins', 'jelixcache.access'); // for admin group
        //jAcl2DbManager::addRight('moderators', 'jelixcache.access');
    }
}