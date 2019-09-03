<?php
/**
 * @package     hfnucontact
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class hfnucontactModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        $config = $helpers->getConfigIni();
        if ($config->getValue('to_contact', 'hfnucontact') === null) {
            $config->setValue('to_contact','', 'hfnucontact');
        }
        if ($config->getValue('email_contact', 'hfnucontact') === null) {
            $config->setValue('email_contact','', 'hfnucontact');
        }
    }
}
