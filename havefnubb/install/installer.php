<?php
/**
* @package   havefnubb
* @author    yourname
* @copyright 2010 yourname
* @link      http://www.yourwebsite.undefined
* @license    All right reserved
*/

require_once (dirname(__FILE__).'./../application-cli.init.php');

$installer = new jInstaller(new textInstallReporter());

$installer->installApplication();

