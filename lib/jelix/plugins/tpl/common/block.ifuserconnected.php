<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Jouanneau Laurent
* @contributor Dominique Papin
* @copyright   2006 Jouanneau laurent
* @copyright   2007 Dominique Papin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_common_ifuserconnected($compiler, $begin, $params=array())
{
	if($begin){
		if(count($params)){
			$content='';
			$compiler->doError1('errors.tplplugin.block.too.many.arguments','ifuserconnected');
		}else{
			$content = ' if(jAuth::isConnected()):';
		}
	}else{
		$content = ' endif; ';
	}
	return $content;
}