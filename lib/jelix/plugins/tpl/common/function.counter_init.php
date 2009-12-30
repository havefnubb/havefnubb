<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Thibault PIRONT < nuKs >
 * @copyright   2007 Thibault PIRONT
 * @link        http://jelix.org/
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_common_counter_init($tpl, $name = '', $type = '0', $start = 1, $incr = 1){
	if(!isset($tpl->_privateVars['counterArray']))
		$tpl->_privateVars['counterArray'] = array( 'default' => array('type' => '0', 'start' => 1, 'incr' => 1));
	if( empty($name) && $name !== '0')
		$name = 'default';
	$tpl->_privateVars['counterArray'][$name] = array( 'type' => $type, 'start' => $start, 'incr' => $incr);
	$in_use = &$tpl->_privateVars['counterArray'][$name];
	if( !is_string($in_use['start']))
	{
		if( $in_use['type'] === 'aa')
			$in_use['start'] = 'a';
		elseif( $in_use['type'] === 'AA')
			$in_use['start'] = 'A';
	}
}