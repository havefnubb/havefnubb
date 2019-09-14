<?php
/**
 * @package     hfnuadmin
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class hfnuadminModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        $helpers->copyFile('hfnuadmin.css', 'www:themes/default/css/hfnuadmin.css', true);
        $helpers->copyDirectoryContent('www/', 'www:hfnu/admin/');
        $helpers->declareGlobalWebAssets('hfnuadmin',
            array(
                'css' => array(
                    '$jelix/design/master_admin.css',
                    '$theme/css/hfnuadmin.css',
                    '$jelix/design/jacl2.css',
                    '$jelix/design/jform.css',
                    '$jelix/design/records_list.css',
                ),
                'js' => array(
                    'hfnu/admin/hfnuadmin.js'
                ),
                'require'=> 'jquery,jqueryui'
            ), 'common', false);
    }
}
