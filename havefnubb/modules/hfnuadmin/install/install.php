<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnuadminModuleInstaller extends jInstallerModule {

    protected $useDatabase = true;

    function postinstall() {
        $this->execSQLscript('sql/postinstall');
    }
}