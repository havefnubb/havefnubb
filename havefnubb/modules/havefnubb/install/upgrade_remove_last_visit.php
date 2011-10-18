<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurentj
* @copyright 2011 Laurentj
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_remove_last_visit extends jInstallerModule {

    public $targetVersions = array('1.4.1b2', '1.5a2');
    public $date = '2011-10-18';

    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("ALTER TABLE ".$cn->prefixTable('community_users')." DROP COLUMN last_visit");
        }
    }
}
