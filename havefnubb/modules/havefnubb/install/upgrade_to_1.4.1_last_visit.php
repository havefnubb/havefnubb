<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_last_visit extends jInstallerModule {

    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("ALTER TABLE ".$cn->prefixTable('community_users')." ADD last_visit INT(12) DEFAULT '0'");
            $cn->exec("UPDATE ".$cn->prefixTable('community_users')." SET last_visit = last_post");
        }
    }
}
