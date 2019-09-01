<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author     Laurent Jouanneau
 * @copyright  2018 Laurent Jouanneau
 * @link       https://jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_html_jurlpattern($tpl,$selector,$params=array(),$placeholders=array())
{
	$search=array();
	$repl=array();
	foreach($placeholders as $par=>$var){
		$params[$par]='__@@'.$var.'@@__';
		$search[]=urlencode($params[$par]);
		$repl[]='%'.$var.'%';
	}
	$url=jUrl::get($selector,$params);
	echo str_replace($search,$repl,$url);
}
