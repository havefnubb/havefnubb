<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright  2011-2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_resurl($tpl,$module,$file,$intheme=false,$escape=true)
{
	if($intheme)
		$file='themes/'.jApp::config()->theme.'/'.$file;
	echo jUrl::get("jelix~www:getfile",array('targetmodule'=>$module,'file'=>$file),($escape?1:0));
}
