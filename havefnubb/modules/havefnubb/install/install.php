<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class havefnubbModuleInstaller extends jInstallerModule {

    protected $useDatabase = true;

    function install() {
        $this->execSQLScript('sql/install');
    }

    function postInstall() {
        $cn = $this->dbConnection();
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (1, 'admins', 'admins', 0, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (2, 'users', 'users', 1, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (3, 'moderators', 'moderators', 0, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (0, 'anonymous', 'anonymous', 0, NULL)");

        // mysql ignore the value 0 and replace it by the next value of auto increment
        // so let's change it to 0
        try {
            $cn->exec("UPDATE ".$cn->prefixTable('jacl2_group')." SET id_aclgrp = 0 WHERE  name = 'anonymous'
                    AND code = 'anonymous' AND grouptype=1 and ownerlogin is null");
        } catch(Exception $e) {}

        if ($cn->dbms == 'pgsql') {
            $cn->exec("SELECT setval('".$cn->prefixTable('jacl2_group_id_aclgrp_seq')."', 2, true)");
        }

        $this->execSQLScript('sql/postinstall.sql');
    }
}