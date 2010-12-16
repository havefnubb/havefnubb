<?php
/**
* @package     hfnuthemes
* @author      Laurent Jouanneau
* @contributor
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnuthemesModuleInstaller extends jInstallerModule {

    function install() {
        if (!$this->getParameter('nocopyfiles') && $this->firstExec('copyfile')) {
            $this->copyDirectoryContent('css/', 'www:themes/default/css/hfnuthemes.css');
        }
    }
}
