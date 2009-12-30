<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @version    $Id$
* @author     Jouanneau Laurent
* @copyright  2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_urljsstring($tpl, $selector, $params=array(), $jsparams=array())
{
	$search = array();
	$repl =array();
	foreach($jsparams as $par=>$var){
		$params[$par] = '__@@'.$var.'@@__';
		$search[] = urlencode($params[$par]);
		$repl[] = '"+encodeURIComponent('.$var.')+"';
	}
	$url = jUrl::get($selector, $params);
	echo '"'.str_replace($search, $repl, $url).'"';
}