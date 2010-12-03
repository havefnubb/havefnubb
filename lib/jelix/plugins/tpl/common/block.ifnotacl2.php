<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @copyright   2006-2008 Laurent Jouanneau
* @copyright   2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_common_ifnotacl2($compiler,$begin,$params=array())
{
	if($begin){
		if(count($params)==1){
			$content=' if(!jAcl2::check('.$params[0].')):';
		}elseif(count($params)==2){
			$content=' if(!jAcl2::check('.$params[0].','.$params[1].')):';
		}else{
			$content='';
			$compiler->doError2('errors.tplplugin.block.bad.argument.number','ifnotacl2',1);
		}
	}else{
		$content=' endif; ';
	}
	return $content;
}
