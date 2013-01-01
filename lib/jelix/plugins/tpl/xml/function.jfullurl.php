<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Mickael Fradin
* @contributor Laurent Jouanneau
* @copyright  2009 Mickael Fradin
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_xml_jfullurl($tpl,$selector,$params=array(),$domain=null,$escape=true){
	echo jUrl::getFull($selector,$params,($escape?1:0),$domain);
}
