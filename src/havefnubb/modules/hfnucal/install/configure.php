<?php
/**
 * @package     hfnucal
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class hfnucalModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
            'nocopyfiles' => false
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        if (!$this->getParameter('nocopyfiles')) {
            $helpers->copyFile('www/themes/default/hfnucal.css', 'www:themes/default/css/hfnucal.css');

            $helpers->declareGlobalWebAssets('hfnuaccount',
                array(
                    'css' => array(
                        '$theme/css/hfnucal.css',
                    ),
                ), 'common', false);
        }


    }
}
