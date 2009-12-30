<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Jouanneau Laurent
* @copyright   2006-2008 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_html_formdata($compiler, $begin, $param=array())
{
	if(!$begin){
		return '
unset($t->_privateVars[\'__form\']); 
unset($t->_privateVars[\'__displayed_ctrl\']);';
	}
	if(count($param) != 1){
		$compiler->doError2('errors.tplplugin.block.bad.argument.number','formdata',1);
		return '';
	}
	$content = ' $t->_privateVars[\'__form\'] = '.$param[0].';
$t->_privateVars[\'__displayed_ctrl\'] = array();
';
	return $content;
}