<?php
/**
 * @package   havefnubb
 * @subpackage activeusers
 * @author   Laurent Jouanneau
 * @copyright 2021 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class activeusersModuleUpgrader_config extends \Jelix\Installer\Module\Installer {

    public $targetVersions = array('1.2.0');

    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers)
    {

        $configIni = $helpers->getLocalConfigIni();

        $iniFile = \jApp::varConfigPath('havefnubb/activeusers.coord.ini.php');
        if (file_exists($iniFile)) {
            $ini = new \Jelix\IniFile\IniModifier($iniFile);
            $configIni->import($ini, 'activeusers');
            unlink($iniFile);
        }
    }
}
