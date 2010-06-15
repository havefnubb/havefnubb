<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class havefnubbModuleInstaller extends jInstallerModule {

    protected $useDatabas= true;

    function install() {
        $this->execSQLScript('install');
    }

    function postInstall() {
        $this->execSQLScript('postinstall');
    }
}