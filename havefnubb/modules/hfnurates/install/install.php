<?php
/**
* @package     havefnubb
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnuratesModuleInstaller extends jInstallerModule {

    function install() {

        if ($this->firstDbExec())
            $this->execSQLScript('sql/install');

        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile')) {
            $this->copyDirectoryContent('www/images/', 'www:images/');
        }
    }
}