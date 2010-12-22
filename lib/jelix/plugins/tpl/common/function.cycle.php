<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Philippe Schelté (dubphil)
 * @copyright   2008 Philippe Schelté
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_common_cycle($tpl,$param=''){
	if(is_array($param)){
		static $cycle_vars;
		if(!isset($cycle_vars['values'])){
			$cycle_vars['values']=$param;
			$cycle_vars['index']=0;
		}
		$retval=$cycle_vars['values'][$cycle_vars['index']];
		if($cycle_vars['index']>=count($cycle_vars['values'])-1){
			$cycle_vars['index']=0;
		}else{
			$cycle_vars['index']++;
		}
	}else{
		$cycle_name=$param ? $param : 'default';
		if(isset($tpl->_privateVars['cycle'][$cycle_name]['values'])){
			$cycle_array=$tpl->_privateVars['cycle'][$cycle_name]['values'];
		}else{
			throw new jException("jelix~errors.tplplugin.function.argument.unknown",array($cycle_name,'cycle',''));
		}
		$index=& $tpl->_privateVars['cycle'][$cycle_name]['index'];
		$retval=$cycle_array[$index];
		if($index>=count($cycle_array)-1){
			$index=0;
		}else{
			$index++;
		}
	}
	echo $retval;
}
