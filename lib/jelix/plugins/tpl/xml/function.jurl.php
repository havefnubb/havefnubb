<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Jouanneau Laurent
* @copyright  2005-2008 Jouanneau laurent
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_xml_jurl($tpl,$selector,$params=array(),$escape=true)
{
	echo jUrl::get($selector,$params,($escape?1:0));
}
