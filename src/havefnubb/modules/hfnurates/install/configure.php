<?php
/**
 * @package     hfnurates
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class hfnuratesModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
            'nocopyfiles' => false
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        if (!$this->getParameter('nocopyfiles')) {
            $helpers->copyDirectoryContent('www/', 'www:hfnu/');
            $helpers->declareGlobalWebAssets('hfnucal',
                array(
                    'css' => array(
                        'hfnu/images/star-rating/jquery.rating.css',
                    ),
                    'js' => array(
                        '$jelix/jquery/include/jquery.include.js',
                        'hfnu/js/jquery.MetaData.js',
                        'hfnu/js/jquery.form.js',
                        'hfnu/js/jquery.rating.pack.js',
                    ),
                    'require'=> 'jquery'
                ), 'common', false);
        }
    }
}
