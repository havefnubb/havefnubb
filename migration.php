<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2010 Olivier Demah
* @link      http://www.havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require ('havefnubb/application.init.php');
jApp::setEnv('webinstall');

require('lib/installwizard/installWizard.php');

$config = 'havefnubb/migration/wizard.ini.php';

$install = new installWizard($config);
$install->run(!isAppInstalled());
