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
function jtpl_function_common_counter_reset_all($tpl){
	if(!isset($tpl->_privateVars['counterArray']))
		return;
	$tpl->_privateVars['counterArray'] = array( 'default' => array('type' => '0', 'start' => 1, 'incr' => 1));
}