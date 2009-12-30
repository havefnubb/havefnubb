<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Jouanneau Laurent
* @contributor Dominique Papin
* @copyright   2006-2007 Jouanneau laurent
* @copyright   2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_common_ifacl($compiler, $begin, $param=array())
{
	if($begin){
		if(count($param) == 2){
			$content = ' if(jAcl::check('.$param[0].','.$param[1].')):';
		}elseif(count($param) == 3){
			$content = ' if(jAcl::check('.$param[0].','.$param[1].','.$param[2].')):';
		}else{
			$content='';
			$compiler->doError2('errors.tplplugin.block.bad.argument.number','ifacl',2);
		}
	}else{
		$content = ' endif; ';
	}
	return $content;
}