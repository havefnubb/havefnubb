<?php
/**
* @package   havefnubb
* @subpackage jelixcache
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class jelixcacheModuleInstaller extends jInstallerModule {

    function install() {
        if ($this->firstExec('acl2')) {
            jAcl2DbManager::addSubject('jelixcache.access', 'jelixcache~jelixcache.acl.access');
            jAcl2DbManager::addRight('admins', 'jelixcache.access'); // for admin group
            //jAcl2DbManager::addRight('moderators', 'jelixcache.access');
        }
    }
}