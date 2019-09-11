<?php
/**
 * @package     hfnucal
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class hfnucalModuleUpgrader extends \Jelix\Installer\Module\Installer
{

    public function install(Jelix\Installer\Module\API\InstallHelpers $helpers)
    {
        if (!$this->getParameter('nocopyfiles')) {
            $helpers->copyFile('www/themes/default/hfnucal.css', 'www:themes/default/css/hfnucal.css');
        }
    }
}
