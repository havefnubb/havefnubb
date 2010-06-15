<?php
/**
* @package     jmessenger
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://bitbucket.org/laurentj/jcommunity/
* @licence      http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/


class jmessengerModuleInstaller extends jInstallerModule {

    protected $useDatabase = true;

    function install() {
        $this->execSQLScript('sql/install');
    }
}