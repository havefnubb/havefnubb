<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author
 * @copyright  2001-2003 ispi of Lincoln, Inc.
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_cfunction_common_include($compiler,$param=array()){
	if(!$compiler->trusted){
		$compiler->doError1('errors.tplplugin.untrusted.not.available','include');
		return '';
	}
	if(count($param)==1){
		return '$t->display('.$param[0].');';
	}else{
		$compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','include','1');
		return '';
	}
}
