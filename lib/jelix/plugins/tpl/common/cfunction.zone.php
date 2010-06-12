<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Jouanneau Laurent
* @copyright   2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_cfunction_common_zone($compiler,$params=array())
{
	if(count($params)==2){
		$content='echo jZone::get('.$params[0].','.$params[1].');';
	}elseif(count($params)==1){
		$content='echo jZone::get('.$params[0].');';
	}else{
		$content='';
		$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','zone','1-2');
	}
	return $content;
}
