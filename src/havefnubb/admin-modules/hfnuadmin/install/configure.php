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
        $helpers->declareGlobalWebAssets('hfnuadmin',
            array(
                'css' => array(
                    '$jelix/design/master_admin.css',
                    'hfnu/admin/css/havefnuboard_admin.css',
                    '$jelix/design/jacl2.css',
                    '$jelix/design/jform.css',
                    '$jelix/design/records_list.css',
                ),
                'require'=> 'jquery,jqueryui'
            ), 'common', false);
    }
}
