<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Julien Issler
 * @copyright   2009 Julien Issler
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_common_repeat_string($tpl,$string='',$count=1){
	echo str_repeat($string,$count);
}
