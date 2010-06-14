<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnucontactModuleInstaller extends jInstallerModule {

    protected $useDatabase = true;

    function install() {
        $this->config->setValue('to_contact','', 'hfnucontact', null, true);
        $this->config->setValue('email_contact','', 'hfnucontact', null, true);
    }

    function postInstall() {
        try {
            $this->execSQLScript('sql/postinstall');
        }
        catch(Exception $e) {}
    }
}