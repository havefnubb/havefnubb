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
use Jelix\Installer\Module\API\LocalConfigurationHelpers;

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
            $config->setValue('to_contact', '', 'hfnucontact');
        }
        if ($config->getValue('email_contact', 'hfnucontact') === null) {
            $config->setValue('email_contact', '', 'hfnucontact');
        }
    }

    public function localConfigure(LocalConfigurationHelpers $helpers)
    {
        $config = $helpers->getConfigIni();
        $cli = $helpers->cli();

        $email = $cli->askInformation(
            'The email where to send contact request from user',
            $config->getValue('email_contact', 'hfnucontact') || '',
            false,
            function ($value) {
                if (jFilter::isEmail($value)) {
                    return $value;
                }
                throw new \Exception('Invalid Email');
            }
        );
        $config->setValue('email_contact', $email, 'hfnucontact');

        $name = $cli->askInformation(
            'The displayed name of the contact',
            $config->getValue('to_contact', 'hfnucontact') || '',
            false
        );
        $config->setValue('to_contact', $name, 'hfnucontact');
    }
}
