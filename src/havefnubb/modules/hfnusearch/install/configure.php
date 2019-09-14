<?php
/**
 * @package     hfnusearch
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class hfnusearchModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
            'nocopyfiles' => false
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        if (!$this->getParameter('nocopyfiles')) {
            $helpers->copyFile('havefnu.search.ini.php.dist', 'appconfig:havefnu.search.ini.php');
            $helpers->copyFile('hfnusearch.css', 'www:themes/default/css/hfnusearch.css', true);
            $helpers->copyFile('hfnusearch.js', 'www:hfnu/js/hfnusearch.js', true);

            $helpers->declareGlobalWebAssets('hfnucal',
                array(
                    'css' => array(
                        '$theme/css/hfnusearch.css',
                        'hfnu/js/jquery.autocomplete.css',
                    ),
                    'js' => array(
                        'hfnu/js/jquery.autocomplete.pack.js',
                        'hfnu/js/hfnusearch.js'
                    ),
                    'require'=> 'jquery'
                ), 'common', false);
        }
    }
}
