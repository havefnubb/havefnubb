<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Mickael Fradin aka kewix
* @contributor Laurent Jouanneau
* @copyright  2009 Mickael Fradin
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_text_jfullurl($tpl,$selector,$params=array(),$domain=false){
	global $gJConfig;
	if(!$domain){
		$domain=$gJConfig->domainName;
	}
	if(!preg_match('/^http/',$domain)){
		$domain=$GLOBALS['gJCoord']->request->getProtocol().$domain;
	}
	echo $domain.jUrl::get($selector,$params,0);
}
