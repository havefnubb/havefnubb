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
function jtpl_function_xml_jlocale($tpl, $locale)
{
	 if(func_num_args() == 3 && is_array(func_get_arg(2))){
		 echo htmlspecialchars(jLocale::get($locale, func_get_arg(2)));
	 }elseif(func_num_args() > 2){
		 $params = func_get_args();
		 unset($params[0]);
		 unset($params[1]);
		 echo htmlspecialchars(jLocale::get($locale, $params));
	 }else{
		 echo htmlspecialchars(jLocale::get($locale));
	 }
}