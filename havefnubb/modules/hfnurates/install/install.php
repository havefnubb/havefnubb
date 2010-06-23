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
echo "  ------ bordel : ";
var_export($this->getParameter('nocopyfiles'));
echo "\n";
var_export(!$this->getParameter('nocopyfiles'));
echo "\n";
var_export($this->config->getValue('hfnucal.installparam','modules'));
echo "\n";
            $this->copyDirectoryContent('www/images/', 'www:images/');
        }
        else echo " ----- no copy\n";
    }
}