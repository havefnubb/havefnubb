<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Laurent Jouanneau
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_html_formdata($compiler,$begin,$param=array())
{
	if(!$begin){
		return '
unset($t->_privateVars[\'__form\']);
unset($t->_privateVars[\'__formbuilder\']);
unset($t->_privateVars[\'__formViewMode\']);
unset($t->_privateVars[\'__displayed_ctrl\']);';
	}
	if(count($param)< 1||count($param)> 3){
		$compiler->doError2('errors.tplplugin.block.bad.argument.number','formdata','1-3');
		return '';
	}
	if(isset($param[1])&&trim($param[1])!='""'&&trim($param[1])!="''")
		$builder=$param[1];
	else
		$builder="'".jApp::config()->tplplugins['defaultJformsBuilder']."'";
	if(isset($param[2]))
		$options=$param[2];
	else
		$options="array()";
	$content=' $t->_privateVars[\'__form\'] = '.$param[0].';
    $t->_privateVars[\'__formViewMode\'] = 1;
    $t->_privateVars[\'__formbuilder\'] = $t->_privateVars[\'__form\']->getBuilder('.$builder.');
    $t->_privateVars[\'__formbuilder\']->setOptions('.$options.');
$t->_privateVars[\'__displayed_ctrl\'] = array();
';
	return $content;
}
