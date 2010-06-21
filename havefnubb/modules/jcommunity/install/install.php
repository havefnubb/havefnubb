<?php
/**
* @package     jcommunity
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://bitbucket.org/laurentj/jcommunity
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class jcommunityModuleInstaller extends jInstallerModule {

    function install() {
        $authconfig = $this->config->getValue('auth','coordplugins');
        $authconfigMaster = $this->config->getValue('auth','coordplugins', null, true);
        $forWS = (in_array($this->entryPoint->type, array('json', 'jsonrpc', 'soap', 'xmlrpc')));

        if (!$authconfig || ($forWS && $authconfig == $authconfigMaster)) {
            //if ($this->entryPoint->type == 'cmdline') {
            //    return;
            //}

            if ($forWS) {
                $pluginIni = 'authsw.coord.ini.php';
            }
            else {
                $pluginIni = 'auth.coord.ini.php';
            }

            $authconfig = dirname($this->entryPoint->configFile).'/'.$pluginIni;
            if ($this->firstExec($authconfig)) {
                // no configuration, let's install the plugin for the entry point
                $this->config->setValue('auth', $authconfig, 'coordplugins');
                $this->copyFile('var/config/'.$pluginIni, 'epconfig:'.$pluginIni);
            }
        }

        $conf = new jIniFileModifier(JELIX_APP_CONFIG_PATH.$authconfig);
        $this->useDbProfile($conf->getValue('profile', 'Db'));

        if ($this->firstExec($authconfig) && $this->getParameter('rewriteconfig')) {
            $conf->setValue('driver', 'Db');
            $conf->setValue('dao','jcommunity~user', 'Db');
            $conf->setValue('error_message', 'jcommunity~login.error.notlogged');
            $conf->setValue('on_error_action', 'jcommunity~login:out');
            $conf->setValue('bad_ip_action', 'jcommunity~login:out');
            $conf->setValue('after_login', 'jcommunity~account:show');
            $conf->setValue('after_logout', 'jcommunity~login:index');
            $conf->setValue('enable_after_login_override', 'on');
            $conf->setValue('enable_after_logout_override', 'on');
            $conf->save();
        }

        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/install');
            if ($this->getParameter('defaultuser')) {
                $cn = $this->dbConnection();
                $cn->exec("INSERT INTO ".$cn->prefixTable('community_users')." (login, password, email ) VALUES
                            ('admin', '".sha1('admin')."' , 'admin@localhost.localdomain')");
            }
        }
    }
}