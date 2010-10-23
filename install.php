<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require ('havefnubb/application.init.php');

require('lib/installwizard/installWizard.php');

$config = 'havefnubb/install/wizard.ini.php';

$install = new installWizard($config);
$install->run(isAppInstalled());
