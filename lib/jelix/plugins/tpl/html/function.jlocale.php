<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Jouanneau Laurent
* @contributor Julien Issler
* @copyright  2005-2007 Jouanneau laurent
* @copyright 2008 Julien Issler
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_jlocale($tpl, $locale)
{
	 if(func_num_args() == 3 && is_array(func_get_arg(2))){
		 $param = func_get_arg(2);
		 echo nl2br(htmlspecialchars(jLocale::get($locale, $param)));
	 }elseif(func_num_args() > 2){
		 $params = func_get_args();
		 unset($params[0]);
		 unset($params[1]);
		 echo nl2br(htmlspecialchars(jLocale::get($locale, $params)));
	 }else{
		 echo nl2br(htmlspecialchars(jLocale::get($locale)));
	 }
}