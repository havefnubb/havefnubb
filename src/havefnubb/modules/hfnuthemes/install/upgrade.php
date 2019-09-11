<?php
/**
 * @package     hfnuthemes
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class hfnuthemesModuleUpgrader extends \Jelix\Installer\Module\Installer
{

    public function install(Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        if (!$this->getParameter('nocopyfiles')) {
            //$helpers->copyDirectoryContent('css/', 'www:themes/default/css/');
        }
    }
}
