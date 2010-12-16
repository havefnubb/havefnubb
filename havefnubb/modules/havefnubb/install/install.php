<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the installation of the database
 */
class havefnubbModuleInstaller extends jInstallerModule {

    function install() {
        if ($this->firstDbExec())
            $this->execSQLScript('sql/install');
    }

    function postInstall() {
        if (!$this->firstDbExec())
            return;
        $cn = $this->dbConnection();
        // mysql ignore the value 0 and replace it by the next value of auto increment
        // so let's change it to 0
        try {
            $cn->exec("UPDATE ".$cn->prefixTable('jacl2_group')." SET id_aclgrp = 0 WHERE  name = 'anonymous'
                    AND code = 'anonymous' AND grouptype=0 and ownerlogin is null");
        } catch(Exception $e) {}

        if ($cn->dbms == 'pgsql') {
            $cn->exec("SELECT setval('".$cn->prefixTable('jacl2_group_id_aclgrp_seq')."', 3, true)");
        }

        $this->execSQLScript('sql/postinstall.sql');
    }
}
