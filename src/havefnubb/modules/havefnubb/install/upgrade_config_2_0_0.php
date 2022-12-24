<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author   Laurent Jouanneau
 * @copyright 2021 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class havefnubbModuleUpgrader_config_2_0_0 extends \Jelix\Installer\Module\Installer {

    public $targetVersions = array('2.0.0-alpha.1');

    function install(\Jelix\Installer\Module\API\InstallHelpers $helpers)
    {

        $configIni = $helpers->getLocalConfigIni();


        $iniFile = \jApp::varConfigPath('havefnubb/flood.coord.ini.php');
        if (file_exists($iniFile)) {
            $ini = new \Jelix\IniFile\IniModifier($iniFile);
            $configIni->import($ini, 'flood');
            unlink($iniFile);
        }

        $iniFile = \jApp::varConfigPath('havefnubb/banuser.coord.ini.php');
        if (file_exists($iniFile)) {
            $ini = new \Jelix\IniFile\IniModifier($iniFile);
            $configIni->import($ini, 'banuser');
            unlink($iniFile);
        }

        $iniFile = \jApp::varConfigPath('havefnubb/history.coord.ini.php');
        if (file_exists($iniFile)) {
            $ini = new \Jelix\IniFile\IniModifier($iniFile);
            $configIni->import($ini, 'history');
            unlink($iniFile);
        }

        $fields = jDao::get('havefnubb~member_custom_fields');
        $fields->deleteByFamilyType('hw:%');
        $fields->deleteByFamilyType('im:%');

        $helpers->database()->execSQLScript('sql/upgrade_2_0');

    }
}
