<?php
/**
* @package     iamhere
* @author      Olivier Demah
* @contributor
* @copyright   2011 Olivier Demah
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class iamhereModuleInstaller extends jInstallerModule {

    function install() {
        if ($this->firstDbExec())
            $this->execSQLscript('sql/install');
        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile'))
            $this->copyFile('www/themes/default/iamhere.css', 'www:themes/default/css/iamhere.css');
            $this->copyFile('tpl/function.imahere.php', 'plugins:tpl/html/function.iamhere.php');
    }
}
