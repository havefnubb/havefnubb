<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurentj
* @copyright 2011 Laurentj
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class activeusersModuleUpgrader_add_disconnection_date extends jInstallerModule {

    public $targetVersions = array('1.1');
    public $date = '2011-10-18';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_add_disconnection_date');
        }
    }
}

