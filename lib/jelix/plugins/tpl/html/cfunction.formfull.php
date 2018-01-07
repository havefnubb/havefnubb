<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Laurent Jouanneau
* @contributor  Dominique Papin, Julien Issler, Bastien Jaillot
* @copyright    2007-2012 Laurent Jouanneau, 2007 Dominique Papin
* @copyright    2008 Julien Issler, 2008 Bastien Jaillot
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_cfunction_html_formfull($compiler,$params=array())
{
	if(count($params)< 2||count($params)> 5){
		$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','formfull','2-5');
	}
	if(isset($params[3])&&trim($params[3])!='""'&&trim($params[3])!="''")
		$builder=$params[3];
	else
		$builder="'".jApp::config()->tplplugins['defaultJformsBuilder']."'";
	if(count($params)==2){
		$params[2]='array()';
	}
	if(isset($params[4]))
		$options=$params[4];
	else
		$options="array()";
	$content=' $formfull = '.$params[0].';
    $formfullBuilder = $formfull->getBuilder('.$builder.');
    $formfullBuilder->setOptions('.$options.');
    $formfullBuilder->setAction('.$params[1].','.$params[2].');
    $formfullBuilder->outputHeader();
    $formfullBuilder->outputAllControls();
    $formfullBuilder->outputFooter();';
	$metacontent='if(isset('.$params[0].')) { $builder = '.$params[0].'->getBuilder('.$builder.');
    $builder->setOptions('.$options.');
    $builder->outputMetaContent($t);}';
	$compiler->addMetaContent($metacontent);
	return $content;
}
