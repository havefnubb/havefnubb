<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnuadminModuleInstaller extends jInstallerModule {

    function postinstall() {
        if ($this->firstDbExec())
            $this->execSQLscript('sql/postinstall');
    }
}