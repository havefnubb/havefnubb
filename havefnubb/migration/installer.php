<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2010 Olivier Demah
* @link      http://www.havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (dirname(__FILE__).'./../application-cli.init.php');

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();

