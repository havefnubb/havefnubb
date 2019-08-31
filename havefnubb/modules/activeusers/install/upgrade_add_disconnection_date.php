<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurentj
* @copyright 2011 Laurentj
* @copyright 2012 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class activeusersModuleUpgrader_add_disconnection_date extends jInstallerModule {

    public $targetVersions = array('1.1');
    //public $date = '2012-03-16';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_add_disconnection_date');
        }
    }
}
