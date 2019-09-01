<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnusearchModuleInstaller extends jInstallerModule {

    function install() {
        if ($this->firstDbExec())
            $this->execSQLScript('sql/install');
        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile')) {
            $this->copyFile('havefnu.search.ini.php.dist', 'config:havefnu.search.ini.php');
            $this->copyFile('hfnusearch.css', 'www:themes/default/css/hfnusearch.css');
        }
    }

    function postInstall() {
        if ($this->firstDbExec())
            $this->execSQLScript('sql/postinstall');
    }
}
