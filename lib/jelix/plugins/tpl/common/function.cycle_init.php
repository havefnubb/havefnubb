<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Philippe SCHELTE < dubphil >
 * @contributor Laurent Jouanneau
 * @copyright   2008 Philippe SCHELTE
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_common_cycle_init($tpl, $name, $values=''){
	if($name == ''){
		throw new jException("jelix~errors.tplplugin.cfunction.bad.argument.number", array('cycle_init','1',''));
	}
	if(strpos($name,',') === false){
		if($values == ''){
			throw new jException("jelix~errors.tplplugin.cfunction.bad.argument.number", array('cycle_init','2',''));
		}
		if(strpos($values,',') === false){
			throw new jException("jelix~errors.tplplugin.function.invalid", array('cycle_init','',''));
		}
	} else{
		$values = $name;
		$name = 'default';
	}
	$tpl->_privateVars['cycle'][$name]['values'] = explode(',',$values);
	$tpl->_privateVars['cycle'][$name]['index'] = 0;
}