<?php
/**
 * @package     havefnubb
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\IniFile\IniModifier;
use Jelix\IniFile\IniReader;
use Jelix\Installer\Module\API\ConfigurationHelpers;

class havefnubbModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
            'ep' => ''
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        $cli = $helpers->cli();
        $this->parameters['ep'] = $cli->askEntryPoints(
            'Select the entry point from which the forum will be accessible',
            $helpers->getEntryPointsByType('classic')
        );
        $epid = $this->parameters['ep'];
        $ep = $helpers->getEntryPointsById($epid);

        /** @var IniModifier $config */
        $config = $ep->getSingleConfigIni();
        $config->setValue('banuser', 1, 'coordplugins');

        $defaultConfig = new IniReader(__DIR__.'/config.ini');
        $helpers->getSingleConfigIni()->import($defaultConfig);

        $helpers->declareGlobalWebAssets('havefnubb',
            array(
                'css' => array(
                    '$theme/css/app.css',
                    '$theme/css/hfnu.css',
                    '$theme/css/nav.css',
                    '$theme/css/theme.css',
                ),
                'require'=> 'jquery'
            ), 'common', false);
        $helpers->declareGlobalWebAssets('hfnuaccount',
            array(
                'css' => array(
                    '$theme/css/tabnav.css',
                ),
                'js' => array(
                    'hfnu/js/accounts.js'
                ),
                'require'=> 'jqueryui'
            ), 'common', false);
        $helpers->declareGlobalWebAssets('hfnumessenger',
            array(
                'require'=> 'jquery'
            ), 'common', false);
    }
}
