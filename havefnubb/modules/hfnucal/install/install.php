<?php
/**
* @package     hfnucal
* @author      Laurent Jouanneau
* @contributor
* @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnucalModuleInstaller extends jInstallerModule {

    function install() {
        $this->copyFile('www/themes/default/hfnucal.css', 'www:themes/default/hfnucal.css');
    }
}