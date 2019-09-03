<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\Installer\Module\API\InstallHelpers;

/**
 * Class that handles the installation of the database
 */
class havefnubbModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function install(InstallHelpers $helpers)
    {
        $helpers->database()->execSQLScript('sql/install');
    }

    public function postInstall(InstallHelpers $helpers)
    {
        $cn = $helpers->database()->dbConnection();
        //groups
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, grouptype, ownerlogin) VALUES ('admins', 'admins', 0, NULL)"); // 1
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, grouptype, ownerlogin) VALUES ('users', 'users', 1, NULL)"); // 2
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, grouptype, ownerlogin) VALUES ('moderators', 'moderators', 0, NULL)"); // 3
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_group')." (id_aclgrp, name, grouptype, ownerlogin) VALUES ('__anonymous', 'anonymous', 0, NULL)"); // 0
        //rights
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.create', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.delete', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.modify', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.group.view', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.modify', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('acl.user.view', 'admins', '')");

        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.change.password', 'users', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.modify', 'users', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.user.view', 'users', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.change.password', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.create', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.delete', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.list', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.modify', 'admins', '')");
        $cn->exec("INSERT INTO ".$cn->prefixTable('jacl2_rights')." (id_aclsbj, id_aclgrp, id_aclres) VALUES ('auth.users.view', 'admins', '')");

        $helpers->database()->execSQLScript('sql/postinstall.sql');
    }
}
