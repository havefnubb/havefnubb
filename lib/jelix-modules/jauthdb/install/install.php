<?php
/**
* @package     jelix
* @subpackage  jauthdb module
* @author      Laurent Jouanneau
* @contributor
* @copyright   2009-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

require_once(dirname(__FILE__).'/_installclass.php');

class jauthdbModuleInstaller extends jauthdbModuleInstallerBase {

    function install() {
        //if ($this->entryPoint->type == 'cmdline')
        //    return;

        $authconfig = $this->config->getValue('auth','coordplugins');

        if ($authconfig) {
            // a config file for the auth plugin exists, so we can install
            // the module, else we ignore it

            $conf = new jIniFileModifier(JELIX_APP_CONFIG_PATH.$authconfig);
            $driver = $conf->getValue('driver');

            if ($driver == '') {
                $driver = 'Db';
                $conf->setValue('driver','Db');
                $conf->setValue('dao','jauthdb~jelixuser', 'Db');
                $conf->save();
            }
            else if ($driver != 'Db') {
                return;
            }

            // FIXME: should use the given dao to create the table
            $daoName = $conf->getValue('dao', 'Db');
            if ($daoName == 'jauthdb~jelixuser') {
                $profile = $conf->getValue('profile', 'Db');
                $this->execSQLScript('install_jauth.schema', $profile);
                try {
                    $this->execSQLScript('install_jauth.data', $profile);
                } catch(Exception $e) {}
            }
        }
    }
}