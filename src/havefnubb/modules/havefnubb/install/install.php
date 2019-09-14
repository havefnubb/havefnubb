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
        $config = $helpers->getLocalConfigIni();
        $config->setValue('version', $this->getVersion(), 'havefnubb');
    }

    public function postInstall(InstallHelpers $helpers)
    {

        jAcl2DbUserGroup::createGroup('admins', 'admins');
        jAcl2DbUserGroup::createGroup('users', 'users');
        jAcl2DbUserGroup::createGroup('moderators', 'moderators');
        jAcl2DbUserGroup::createGroup('anonymous', '__anonymous');

        jAcl2DbManager::addRole('hfnu.category.list', 'havefnubb~acl2.category.list');
        jAcl2DbManager::addRole('hfnu.category.view', 'havefnubb~acl2.category.view');
        jAcl2DbManager::addRole('hfnu.forum.goto', 'havefnubb~acl2.forum.goto');
        jAcl2DbManager::addRole('hfnu.forum.list', 'havefnubb~acl2.forum.list');
        jAcl2DbManager::addRole('hfnu.forum.view', 'havefnubb~acl2.forum.view');
        jAcl2DbManager::addRole('hfnu.member.list', 'havefnubb~acl2.member.list');
        jAcl2DbManager::addRole('hfnu.member.search', 'havefnubb~acl2.member.search');
        jAcl2DbManager::addRole('hfnu.member.view', 'havefnubb~acl2.member.view');
        jAcl2DbManager::addRole('hfnu.posts.create', 'havefnubb~acl2.posts.create');
        jAcl2DbManager::addRole('hfnu.posts.delete', 'havefnubb~acl2.posts.delete');
        jAcl2DbManager::addRole('hfnu.posts.edit', 'havefnubb~acl2.posts.edit');
        jAcl2DbManager::addRole('hfnu.posts.list', 'havefnubb~acl2.posts.list');
        jAcl2DbManager::addRole('hfnu.posts.moderate', 'havefnubb~acl2.posts.moderate');
        jAcl2DbManager::addRole('hfnu.posts.notify', 'havefnubb~acl2.posts.notify');
        jAcl2DbManager::addRole('hfnu.posts.quote', 'havefnubb~acl2.posts.quote');
        jAcl2DbManager::addRole('hfnu.posts.reply', 'havefnubb~acl2.posts.reply');
        jAcl2DbManager::addRole('hfnu.posts.view', 'havefnubb~acl2.posts.view');
        jAcl2DbManager::addRole('hfnu.posts.rss', 'havefnubb~acl2.posts.rss');
        jAcl2DbManager::addRole('hfnu.posts.edit.own', 'havefnubb~acl2.posts.edit.own');
        jAcl2DbManager::addRole('hfnu.admin.post', 'havefnubb~acl2.admin.post');


        jAcl2DbManager::addRight('admins', 'acl.group.create');
        jAcl2DbManager::addRight('admins', 'acl.group.delete');
        jAcl2DbManager::addRight('admins', 'acl.group.modify');
        jAcl2DbManager::addRight('admins', 'acl.group.view');
        jAcl2DbManager::addRight('admins', 'acl.user.modify');
        jAcl2DbManager::addRight('admins', 'acl.user.view');
        jAcl2DbManager::addRight('admins', 'auth.user.change.password');
        jAcl2DbManager::addRight('admins', 'auth.user.modify');
        jAcl2DbManager::addRight('admins', 'auth.user.view');
        jAcl2DbManager::addRight('admins', 'auth.users.change.password');
        jAcl2DbManager::addRight('admins', 'auth.users.create');
        jAcl2DbManager::addRight('admins', 'auth.users.delete');
        jAcl2DbManager::addRight('admins', 'auth.users.list');
        jAcl2DbManager::addRight('admins', 'auth.users.modify');
        jAcl2DbManager::addRight('admins', 'auth.users.view');

        jAcl2DbManager::addRight('users', 'auth.user.change.password');
        jAcl2DbManager::addRight('users', 'auth.user.modify');
        jAcl2DbManager::addRight('users', 'auth.user.view');


        $rights = array(
             array('hfnu.admin.post', 'admins', ''),
             array('hfnu.admin.post', 'moderators', ''),
             array('hfnu.forum.goto', 'admins', ''),
             array('hfnu.forum.goto', 'users', ''),
             array('hfnu.forum.goto', 'moderators', ''),
             array('hfnu.forum.list', '__anonymous', 'forum1'),
             array('hfnu.forum.list', '__anonymous', 'forum2'),
             array('hfnu.forum.list', '__anonymous', 'forum3'),
             array('hfnu.forum.list', '__anonymous', 'forum4'),
             array('hfnu.forum.list', 'admins', 'forum1'),
             array('hfnu.forum.list', 'admins', 'forum2'),
             array('hfnu.forum.list', 'admins', 'forum3'),
             array('hfnu.forum.list', 'admins', 'forum4'),
             array('hfnu.forum.list', 'users', 'forum1'),
             array('hfnu.forum.list', 'users', 'forum2'),
             array('hfnu.forum.list', 'users', 'forum3'),
             array('hfnu.forum.list', 'users', 'forum4'),
             array('hfnu.forum.list', 'moderators', 'forum1'),
             array('hfnu.forum.list', 'moderators', 'forum2'),
             array('hfnu.forum.list', 'moderators', 'forum3'),
             array('hfnu.forum.list', 'moderators', 'forum4'),
             array('hfnu.forum.view', '__anonymous', 'forum1'),
             array('hfnu.forum.view', '__anonymous', 'forum2'),
             array('hfnu.forum.view', '__anonymous', 'forum3'),
             array('hfnu.forum.view', '__anonymous', 'forum4'),
             array('hfnu.forum.view', 'admins', 'forum1'),
             array('hfnu.forum.view', 'admins', 'forum2'),
             array('hfnu.forum.view', 'admins', 'forum3'),
             array('hfnu.forum.view', 'admins', 'forum4'),
             array('hfnu.forum.view', 'users', 'forum1'),
             array('hfnu.forum.view', 'users', 'forum2'),
             array('hfnu.forum.view', 'users', 'forum3'),
             array('hfnu.forum.view', 'users', 'forum4'),
             array('hfnu.forum.view', 'moderators', 'forum1'),
             array('hfnu.forum.view', 'moderators', 'forum2'),
             array('hfnu.forum.view', 'moderators', 'forum3'),
             array('hfnu.forum.view', 'moderators', 'forum4'),
             array('hfnu.member.list', 'admins', ''),
             array('hfnu.member.list', 'users', ''),
             array('hfnu.member.list', 'moderators', ''),
             array('hfnu.member.search', 'admins', ''),
             array('hfnu.member.search', 'users', ''),
             array('hfnu.member.search', 'moderators', ''),
             array('hfnu.member.view', 'admins', ''),
             array('hfnu.member.view', 'users', ''),
             array('hfnu.member.view', 'moderators', ''),
             array('hfnu.posts.create', 'admins', 'forum1'),
             array('hfnu.posts.create', 'admins', 'forum2'),
             array('hfnu.posts.create', 'admins', 'forum3'),
             array('hfnu.posts.create', 'admins', 'forum4'),
             array('hfnu.posts.create', 'users', 'forum1'),
             array('hfnu.posts.create', 'users', 'forum2'),
             array('hfnu.posts.create', 'users', 'forum3'),
             array('hfnu.posts.create', 'users', 'forum4'),
             array('hfnu.posts.create', 'moderators', 'forum1'),
             array('hfnu.posts.create', 'moderators', 'forum2'),
             array('hfnu.posts.create', 'moderators', 'forum3'),
             array('hfnu.posts.create', 'moderators', 'forum4'),
             array('hfnu.posts.delete', 'admins', 'forum1'),
             array('hfnu.posts.delete', 'admins', 'forum2'),
             array('hfnu.posts.delete', 'admins', 'forum3'),
             array('hfnu.posts.delete', 'admins', 'forum4'),
             array('hfnu.posts.edit', 'admins', 'forum1'),
             array('hfnu.posts.edit', 'admins', 'forum2'),
             array('hfnu.posts.edit', 'admins', 'forum3'),
             array('hfnu.posts.edit', 'admins', 'forum4'),
             array('hfnu.posts.edit', 'moderators', 'forum1'),
             array('hfnu.posts.edit', 'moderators', 'forum2'),
             array('hfnu.posts.edit', 'moderators', 'forum3'),
             array('hfnu.posts.edit', 'moderators', 'forum4'),
             array('hfnu.posts.edit.own', 'admins', 'forum1'),
             array('hfnu.posts.edit.own', 'admins', 'forum2'),
             array('hfnu.posts.edit.own', 'admins', 'forum3'),
             array('hfnu.posts.edit.own', 'admins', 'forum4'),
             array('hfnu.posts.edit.own', 'users', 'forum1'),
             array('hfnu.posts.edit.own', 'users', 'forum2'),
             array('hfnu.posts.edit.own', 'users', 'forum3'),
             array('hfnu.posts.edit.own', 'users', 'forum4'),
             array('hfnu.posts.edit.own', 'moderators', 'forum1'),
             array('hfnu.posts.edit.own', 'moderators', 'forum2'),
             array('hfnu.posts.edit.own', 'moderators', 'forum3'),
             array('hfnu.posts.edit.own', 'moderators', 'forum4'),
             array('hfnu.posts.list', '__anonymous', 'forum1'),
             array('hfnu.posts.list', '__anonymous', 'forum2'),
             array('hfnu.posts.list', '__anonymous', 'forum3'),
             array('hfnu.posts.list', '__anonymous', 'forum4'),
             array('hfnu.posts.list', 'admins', 'forum1'),
             array('hfnu.posts.list', 'admins', 'forum2'),
             array('hfnu.posts.list', 'admins', 'forum3'),
             array('hfnu.posts.list', 'admins', 'forum4'),
             array('hfnu.posts.list', 'users', 'forum1'),
             array('hfnu.posts.list', 'users', 'forum2'),
             array('hfnu.posts.list', 'users', 'forum3'),
             array('hfnu.posts.list', 'users', 'forum4'),
             array('hfnu.posts.list', 'moderators', 'forum1'),
             array('hfnu.posts.list', 'moderators', 'forum2'),
             array('hfnu.posts.list', 'moderators', 'forum3'),
             array('hfnu.posts.list', 'moderators', 'forum4'),
             array('hfnu.posts.notify', 'admins', 'forum1'),
             array('hfnu.posts.notify', 'admins', 'forum2'),
             array('hfnu.posts.notify', 'admins', 'forum3'),
             array('hfnu.posts.notify', 'admins', 'forum4'),
             array('hfnu.posts.notify', 'users', 'forum1'),
             array('hfnu.posts.notify', 'users', 'forum2'),
             array('hfnu.posts.notify', 'users', 'forum3'),
             array('hfnu.posts.notify', 'users', 'forum4'),
             array('hfnu.posts.notify', 'moderators', 'forum1'),
             array('hfnu.posts.notify', 'moderators', 'forum2'),
             array('hfnu.posts.notify', 'moderators', 'forum3'),
             array('hfnu.posts.notify', 'moderators', 'forum4'),
             array('hfnu.posts.quote', 'admins', 'forum1'),
             array('hfnu.posts.quote', 'admins', 'forum2'),
             array('hfnu.posts.quote', 'admins', 'forum3'),
             array('hfnu.posts.quote', 'admins', 'forum4'),
             array('hfnu.posts.quote', 'users', 'forum1'),
             array('hfnu.posts.quote', 'users', 'forum2'),
             array('hfnu.posts.quote', 'users', 'forum3'),
             array('hfnu.posts.quote', 'users', 'forum4'),
             array('hfnu.posts.quote', 'moderators', 'forum1'),
             array('hfnu.posts.quote', 'moderators', 'forum2'),
             array('hfnu.posts.quote', 'moderators', 'forum3'),
             array('hfnu.posts.quote', 'moderators', 'forum4'),
             array('hfnu.posts.reply', 'admins', 'forum1'),
             array('hfnu.posts.reply', 'admins', 'forum2'),
             array('hfnu.posts.reply', 'admins', 'forum3'),
             array('hfnu.posts.reply', 'admins', 'forum4'),
             array('hfnu.posts.reply', 'users', 'forum1'),
             array('hfnu.posts.reply', 'users', 'forum2'),
             array('hfnu.posts.reply', 'users', 'forum3'),
             array('hfnu.posts.reply', 'users', 'forum4'),
             array('hfnu.posts.reply', 'moderators', 'forum1'),
             array('hfnu.posts.reply', 'moderators', 'forum2'),
             array('hfnu.posts.reply', 'moderators', 'forum3'),
             array('hfnu.posts.reply', 'moderators', 'forum4'),
             array('hfnu.posts.rss', '__anonymous', 'forum1'),
             array('hfnu.posts.rss', '__anonymous', 'forum2'),
             array('hfnu.posts.rss', '__anonymous', 'forum3'),
             array('hfnu.posts.rss', '__anonymous', 'forum4'),
             array('hfnu.posts.rss', 'admins', 'forum1'),
             array('hfnu.posts.rss', 'admins', 'forum2'),
             array('hfnu.posts.rss', 'admins', 'forum3'),
             array('hfnu.posts.rss', 'admins', 'forum4'),
             array('hfnu.posts.rss', 'users', 'forum1'),
             array('hfnu.posts.rss', 'users', 'forum2'),
             array('hfnu.posts.rss', 'users', 'forum3'),
             array('hfnu.posts.rss', 'users', 'forum4'),
             array('hfnu.posts.rss', 'moderators', 'forum1'),
             array('hfnu.posts.rss', 'moderators', 'forum2'),
             array('hfnu.posts.rss', 'moderators', 'forum3'),
             array('hfnu.posts.rss', 'moderators', 'forum4'),
             array('hfnu.posts.view', '__anonymous', 'forum1'),
             array('hfnu.posts.view', '__anonymous', 'forum2'),
             array('hfnu.posts.view', '__anonymous', 'forum3'),
             array('hfnu.posts.view', '__anonymous', 'forum4'),
             array('hfnu.posts.view', 'admins', 'forum1'),
             array('hfnu.posts.view', 'admins', 'forum2'),
             array('hfnu.posts.view', 'admins', 'forum3'),
             array('hfnu.posts.view', 'admins', 'forum4'),
             array('hfnu.posts.view', 'users', 'forum1'),
             array('hfnu.posts.view', 'users', 'forum2'),
             array('hfnu.posts.view', 'users', 'forum3'),
             array('hfnu.posts.view', 'users', 'forum4'),
             array('hfnu.posts.view', 'moderators', 'forum1'),
             array('hfnu.posts.view', 'moderators', 'forum2'),
             array('hfnu.posts.view', 'moderators', 'forum3'),
             array('hfnu.posts.view', 'moderators', 'forum4'),
        );

        foreach($rights as $right) {
            list($role, $group, $res) = $right;
            if ($res != '') {
                jAcl2DbManager::addRight($group, $role, $res);
            }
            else {
                jAcl2DbManager::addRight($group, $role);
            }
        }

        $helpers->database()->execSQLScript('sql/postinstall.sql');
    }
}
