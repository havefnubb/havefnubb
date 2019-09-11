<?php
/**
* @package     hafnuthemes
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010-2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

class hfnuthemesModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function install(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('hfnu.admin.themes', 'hfnuthemes~theme.admin.themes');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.themes'); // for admin group
    }
}
