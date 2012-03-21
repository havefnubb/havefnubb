<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2008-2012 Olivier Demah
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_last_visit extends jInstallerModule {

    public $targetVersions = array('1.4.1a1', '1.5a1');
    //public $date = '2011-09-29';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_to_1.4.1a1_last_visit');
            $cn = $this->dbConnection();
            $cn->exec("UPDATE ".$cn->prefixTable('community_users')." SET last_visit = last_post");
        }
    }
}
