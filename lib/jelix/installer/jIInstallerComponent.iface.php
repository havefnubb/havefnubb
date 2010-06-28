<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  installer
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2009 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
interface jIInstallerComponent{
	function preInstall();
	function install();
	function postInstall();
	function preUninstall();
	function uninstall();
	function postUninstall();
}
