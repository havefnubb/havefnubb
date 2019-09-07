<?php
/**
 * @package     hfnuthemes
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class hfnuthemesModuleUpgrader_rights extends \Jelix\Installer\Module\Installer
{
    protected $targetVersions = array('1.5.0');

    protected $date = '2019-09-07 10:30';
    public function install(Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        // replace role hfnu.admin.themes by hfnuthemes.admin.themes
        jAcl2DbManager::addRole('hfnuthemes.admin.themes', 'hfnuthemes~theme.admin.themes');
        jAcl2DbManager::copyRoleRights('hfnu.admin.themes', 'hfnuthemes.admin.themes');
        jAcl2DbManager::removeRole('hfnu.admin.themes');
    }
}
