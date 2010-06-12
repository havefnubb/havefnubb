<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Laurent Jouanneau
* @contributor  Dominique Papin, Julien Issler, Bastien Jaillot
* @copyright    2007-2008 Laurent Jouanneau, 2007 Dominique Papin
* @copyright    2008 Julien Issler, 2008 Bastien Jaillot
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_cfunction_html_formfull($compiler,$params=array())
{
	global $gJConfig;
	if(count($params)< 2||count($params)> 5){
		$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','formfull','2-5');
	}
	if(isset($params[3])&&trim($params[3])!='""'&&trim($params[3])!="''")
		$builder=$params[3];
	else
		$builder="'".$gJConfig->tplplugins['defaultJformsBuilder']."'";
	$compiler->addMetaContent('if(isset('.$params[0].')) { '.$params[0].'->getBuilder('.$builder.')->outputMetaContent($t);}');
	if(count($params)==2){
		$params[2]='array()';
	}
	if(isset($params[4]))
		$options=$params[4];
	else
		$options="array()";
	$content=' $formfull = '.$params[0].';
    $formfullBuilder = $formfull->getBuilder('.$builder.');
    $formfullBuilder->setAction('.$params[1].','.$params[2].');
    $formfullBuilder->outputHeader('.$options.');
    $formfullBuilder->outputAllControls();
    $formfullBuilder->outputFooter();';
	return $content;
}
