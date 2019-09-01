<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_removeconnected extends jInstallerModule {

    public $targetVersions = array('1.4b1.1');


    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("DROP TABLE IF EXISTS ".$cn->prefixTable('hfn_connected'));
            $cn->exec("DROP TABLE IF EXISTS ".$cn->prefixTable('connected'));
            $cn->exec("ALTER TABLE ".$cn->prefixTable('community_users')." DROP   `last_connect`");
        }
    }
}
