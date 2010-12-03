<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @version    $Id$
* @author     Laurent Jouanneau
* @copyright  2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_xul_jurl($tpl,$selector,$params=array(),$escape=true)
{
	echo jUrl::get($selector,$params,($escape?1:0));
}
