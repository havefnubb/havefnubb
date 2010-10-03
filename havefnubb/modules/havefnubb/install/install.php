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
        //groups
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (1, 'admins', 'admins', 0, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (2, 'users', 'users', 1, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (3, 'moderators', 'moderators', 0, NULL)");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, code, grouptype, ownerlogin) VALUES (0, 'anonymous', 'anonymous', 0, NULL)");
        //rights
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.create', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.delete', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.modify', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.view', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.modify', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.view', 1, '')");

        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 2, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 2, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 2, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.change.password', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.create', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.delete', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.list', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.modify', 1, '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.view', 1, '')");

        // mysql ignore the value 0 and replace it by the next value of auto increment
        // so let's change it to 0
        try {
            $cn->exec("UPDATE ".$cn->prefixTable('jacl2_group')." SET id_aclgrp = 0 WHERE  name = 'anonymous'
                    AND code = 'anonymous' AND grouptype=0 and ownerlogin is null");
        } catch(Exception $e) {}

        if ($cn->dbms == 'pgsql') {
            $cn->exec("SELECT setval('".$cn->prefixTable('jacl2_group_id_aclgrp_seq')."', 2, true)");
        }

        $this->execSQLScript('sql/postinstall.sql');
    }
}
