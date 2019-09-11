<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    Laurent Jouanneau
 * @copyright 2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\InstallHelpers;

/**
 * Class that handles the installation of the database
 */
class havefnubbModuleUpgrader extends \Jelix\Installer\Module\Installer
{

    public function install(InstallHelpers $helpers)
    {
        $config = $helpers->getLocalConfigIni();
        $config->setValue('version', $this->getVersion(), 'havefnubb');
    }
}
