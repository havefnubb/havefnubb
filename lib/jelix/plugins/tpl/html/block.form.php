<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Laurent Jouanneau
* @contributor Julien Issler, Bastien Jaillot, Dominique Papin
* @copyright   2006-2012 Laurent Jouanneau
* @copyright   2008 Julien Issler, 2008 Bastien Jaillot, 2008 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_html_form($compiler,$begin,$param=array())
{
	if(!$begin){
		return '$t->_privateVars[\'__formbuilder\']->outputFooter();
unset($t->_privateVars[\'__form\']);
unset($t->_privateVars[\'__formbuilder\']);
unset($t->_privateVars[\'__displayed_ctrl\']);';
	}
	if(count($param)< 2||count($param)> 5){
		$compiler->doError2('errors.tplplugin.block.bad.argument.number','form','2-5');
		return '';
	}
	if(count($param)==2){
		$param[2]='array()';
	}
	if(isset($param[3])&&trim($param[3])!='""'&&trim($param[3])!="''")
		$builder=$param[3];
	else
		$builder="'".jApp::config()->tplplugins['defaultJformsBuilder']."'";
	if(isset($param[4]))
		$options=$param[4];
	else
		$options="array()";
	$content=' $t->_privateVars[\'__form\'] = '.$param[0].';
$t->_privateVars[\'__formbuilder\'] = $t->_privateVars[\'__form\']->getBuilder('.$builder.');
$t->_privateVars[\'__formbuilder\']->setOptions('.$options.');
$t->_privateVars[\'__formbuilder\']->setAction('.$param[1].','.$param[2].');
$t->_privateVars[\'__formbuilder\']->outputHeader();
$t->_privateVars[\'__displayed_ctrl\'] = array();
';
	$metacontent='if(isset('.$param[0].')) { $builder = '.$param[0].'->getBuilder('.$builder.');
    $builder->setOptions('.$options.');
    $builder->outputMetaContent($t);}';
	$compiler->addMetaContent($metacontent);
	return $content;
}
