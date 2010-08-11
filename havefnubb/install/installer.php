<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Boostrap file that handle the installation process
 */
require_once (dirname(__FILE__).'./../application-cli.init.php');

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();
