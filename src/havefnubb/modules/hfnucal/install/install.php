<?php
/**
* @package     hfnucal
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnucalModuleInstaller extends jInstallerModule {

    function install() {
        //if ($this->firstDbExec())
        //    $this->execSQLscript('sql/install');
        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile'))
            $this->copyFile('www/themes/default/hfnucal.css', 'www:themes/default/css/hfnucal.css');
    }
}