<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Julien Issler
 * @copyright   2009 Julien Issler
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_cfunction_common_fetchzone($compiler,$params=array()){
	if(count($params)==3)
		return '$t->_vars['.$params[0].'] = jZone::get('.$params[1].','.$params[2].');';
	else if(count($params)==2)
		return '$t->_vars['.$params[0].'] = jZone::get('.$params[1].');';
	$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','fetchzone','2-3');
	return '';
}
