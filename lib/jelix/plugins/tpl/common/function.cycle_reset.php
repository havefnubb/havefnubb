<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Philippe SCHELTE < dubphil >
 * @copyright   2008 Philippe SCHELTE
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_common_cycle_reset($tpl, $name=''){
	$cycle_name = $name ? $name : 'default';
	if(isset($tpl->_privateVars['cycle'][$cycle_name])){
		$tpl->_privateVars['cycle'][$cycle_name]['index'] = 0;
	} else{
		throw new jException("jelix~errors.tplplugin.function.argument.unknow", array($cycle_name,'cycle',''));
	}
}