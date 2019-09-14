<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010-2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


use Jelix\Installer\Module\API\InstallHelpers;

class hfnuadminModuleInstaller extends \Jelix\Installer\Module\Installer {

    public function postInstall(InstallHelpers $helpers)
    {
        jAcl2DbManager::addRole('hfnu.admin.ban', 'havefnubb~acl2.admin.ban');
        jAcl2DbManager::addRole('hfnu.admin.category', 'havefnubb~acl2.admin.category');
        jAcl2DbManager::addRole('hfnu.admin.category.create', 'havefnubb~acl2.admin.category.create');
        jAcl2DbManager::addRole('hfnu.admin.category.delete', 'havefnubb~acl2.admin.category.delete');
        jAcl2DbManager::addRole('hfnu.admin.category.edit', 'havefnubb~acl2.admin.category.edit');
        jAcl2DbManager::addRole('hfnu.admin.config', 'havefnubb~acl2.admin.config');
        jAcl2DbManager::addRole('hfnu.admin.config.edit', 'havefnubb~acl2.admin.config.edit');
        jAcl2DbManager::addRole('hfnu.admin.forum', 'havefnubb~acl2.admin.forum');
        jAcl2DbManager::addRole('hfnu.admin.forum.create', 'havefnubb~acl2.admin.forum.create');
        jAcl2DbManager::addRole('hfnu.admin.forum.delete', 'havefnubb~acl2.admin.forum.delete');
        jAcl2DbManager::addRole('hfnu.admin.forum.edit', 'havefnubb~acl2.admin.forum.edit');
        jAcl2DbManager::addRole('hfnu.admin.index', 'havefnubb~acl2.admin.index');
        jAcl2DbManager::addRole('hfnu.admin.member', 'havefnubb~acl2.admin.member');
        jAcl2DbManager::addRole('hfnu.admin.notify.delete', 'havefnubb~acl2.admin.notify.delete');
        jAcl2DbManager::addRole('hfnu.admin.notify.list', 'havefnubb~acl2.admin.notify.list');
        jAcl2DbManager::addRole('hfnu.admin.rank.create', 'havefnubb~acl2.admin.rank.create');
        jAcl2DbManager::addRole('hfnu.admin.rank.delete', 'havefnubb~acl2.admin.rank.delete');
        jAcl2DbManager::addRole('hfnu.admin.rank.edit', 'havefnubb~acl2.admin.rank.edit');

        
        jAcl2DbManager::addRight('admins', 'hfnu.admin.ban');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.category');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.category.create');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.category.delete');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.category.edit');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.config');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.config.edit');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.forum');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.forum.create');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.forum.delete');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.forum.edit');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.index');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.member');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.notify.delete');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.notify.list');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.rank.create');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.rank.delete');
        jAcl2DbManager::addRight('admins', 'hfnu.admin.rank.edit');


        jAcl2DbManager::addRight('moderators', 'hfnu.admin.ban');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.category');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.category.create');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.category.delete');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.category.edit');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.config');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.index');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.member');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.notify.delete');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.notify.list');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.rank.create');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.rank.delete');
        jAcl2DbManager::addRight('moderators', 'hfnu.admin.rank.edit');
    }
}