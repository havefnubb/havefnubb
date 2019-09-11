<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    Laurent Jouanneau
 * @copyright 2018-2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class havefnubbModuleUpgrader_passwordfield extends jInstallerModule {

    public $targetVersions = array('1.5.2');
    public $date = '2018-01-07';

    function install() {
        if ($this->firstDbExec()) {
            $isJelix17 = method_exists('jApp', 'appSystemPath');
            $authconfig = $this->config->getValue('auth','coordplugins');
            if ($isJelix17) {
                $confPath = jApp::appSystemPath($authconfig);
                $conf = new \Jelix\IniFile\IniModifier($confPath);
            }
            else {
                $confPath = jApp::configPath($authconfig);
                $conf = new jIniFileModifier($confPath);
            }
            $dbProfile = $conf->getValue('profile', 'Db');
            $this->useDbProfile($dbProfile);

            $db = $this->dbConnection();
            $table = $db->schema()->getTable('community_users');
            $col = $table->getColumn('password', true);
            if ($col->length < 150) {
                $col->length = 150;
                $table->alterColumn($col);
            }
        }
    }
}
