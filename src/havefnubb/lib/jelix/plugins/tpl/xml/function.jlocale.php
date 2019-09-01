<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright  2005-2011 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_xml_jlocale($tpl,$locale)
{
	if(func_num_args()==4&&is_array(func_get_arg(2))){
		$param2=func_get_arg(2);
		$param3=func_get_arg(3);
		echo htmlspecialchars(jLocale::get($locale,$param2,$param3));
	}elseif(func_num_args()==3&&is_array(func_get_arg(2))){
		$param=func_get_arg(2);
		echo htmlspecialchars(jLocale::get($locale,$param));
	}elseif(func_num_args()> 2){
		$params=func_get_args();
		unset($params[0]);
		unset($params[1]);
		echo htmlspecialchars(jLocale::get($locale,$params));
	}else{
		echo htmlspecialchars(jLocale::get($locale));
	}
}
