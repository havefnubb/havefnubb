<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   core
* @author       Laurent Jouanneau
* @copyright    2012 Laurent Jouanneau
* @link         http://jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
namespace jelix\core;
interface ConfigCompilerPluginInterface{
	function getPriority();
	function atStart($config);
	function onModule($config,$moduleName,$modulePath,$moduleXml);
	function atEnd($config);
}
