<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Laurent Jouanneau
* @contributor  Yann (description and keywords), Dominique Papin (ie7 support), Mickaël Fradin (style), Loic Mathaud (title), Olivier Demah (auhor,generator), Julien Issler
* @copyright    2005-2012 Laurent Jouanneau, 2007 Dominique Papin, 2008 Mickaël Fradin, 2009 Loic Mathaud, 2010 Olivier Demah
* @copyright    2010 Julien Issler
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_meta_html_htmlmodule($tpl,$method,$module,$path,$params=array())
{
	$resp=jApp::coord()->response;
	if($resp->getType()!='html'){
		return;
	}
	if(strpos($method,'csstheme')===0){
		$url=jUrl::get('jelix~www:getfile',array('targetmodule'=>$module,'file'=>'themes/'.jApp::config()->theme.'/'.$path));
		switch($method){
			case 'csstheme':
				$resp->addCSSLink($url,$params);
				break;
			case 'cssthemeie':
				$resp->addCSSLink($url,$params,true);
				break;
			case 'cssthemeie7':
			case 'cssthemeie8':
			case 'cssthemeie9':
				$resp->addCSSLink($url,$params,'IE '.substr($method,-1,1));
				break;
			case 'cssthemeltie7':
			case 'cssthemeltie8':
			case 'cssthemeltie9':
				$resp->addCSSLink($url,$params,'lt IE '.substr($method,-1,1));
				break;
			default:
				trigger_error("Unknown resource type in meta_htmlmodule",E_USER_WARNING);
		}
	}
	else{
		$url=jUrl::get('jelix~www:getfile',array('targetmodule'=>$module,'file'=>$path));
		switch($method){
			case 'js':
				$resp->addJSLink($url,$params);
				break;
			case 'css':
				$resp->addCSSLink($url,$params);
				break;
			case 'jsie':
				$resp->addJSLink($url,$params,true);
				break;
			case 'cssie':
				$resp->addCSSLink($url,$params,true);
				break;
			case 'cssie7':
			case 'cssie8':
			case 'cssie9':
				$resp->addCSSLink($url,$params,'IE '.substr($method,-1,1));
				break;
			case 'cssltie7':
			case 'cssltie8':
			case 'cssltie9':
				$resp->addCSSLink($url,$params,'lt IE '.substr($method,-1,1));
				break;
			default:
				trigger_error("Unknown resource type in meta_htmlmodule",E_USER_WARNING);
		}
	}
}
