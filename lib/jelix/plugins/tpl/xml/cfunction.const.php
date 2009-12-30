<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * display a constant
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author      Laurent Jouanneau
 * @copyright  2008 Laurent Jouanneau
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_cfunction_xml_const($compiler, $param=array()){
	if(!$compiler->trusted){
		$compiler->doError1('errors.tplplugin.untrusted.not.available','const');
		return '';
	}
	if(count($param) == 1){
		return 'echo htmlspecialchars(constant('.$param[0].'));';
	}else{
		$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','const','1');
		return '';
	}
}