<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnusearchModuleInstaller extends jInstallerModule {

    protected $forEachEntryPointsConfig = true;

    protected $useDatabase = true;

    function install() {
        $this->execSQLScript('sql/install');
        $this->copyFile('havefnu.search.ini.php.dist', 'config:havefnu.search.ini.php');
        $this->copyFile('hfnusearch.css', 'www:themes/default/hfnusearch.css');
    }

    function postInstall() {
        $this->execSQLScript('sql/postinstall');
    }
}