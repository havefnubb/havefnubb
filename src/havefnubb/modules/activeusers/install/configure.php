<?php
/**
 * @package     activeusers
 * @author      Laurent Jouanneau
 * @contributor
 * @copyright   2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Jelix\Installer\Module\API\ConfigurationHelpers;

class activeusersModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters()
    {
        return array(
            'nocopyfiles' => false,
            'ep' => array()
        );
    }

    public function configure(ConfigurationHelpers $helpers)
    {
        $eplist = $this->getParameter('ep');
        if ($eplist) {
            if (!is_array($eplist)) {
                $eplist = array($eplist);
            }
            foreach ($eplist as $epid) {
                $ep = $helpers->getEntryPointsById($epid);
                $config = $ep->getConfigIni();
                $config->setValue('activeusers', 1, 'coordplugins');
                if ($config->getValue('timeout_visit', 'activeusers') === null) {
                    $config->setValue('timeout_visit', 3600, 'activeusers');
                }
            }
        }

        $helpers->copyFile('botsagent.ini.php', 'appconfig:botsagent.ini.php');
    }
}
