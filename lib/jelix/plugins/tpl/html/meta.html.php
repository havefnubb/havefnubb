<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Jouanneau Laurent
* @contributor  Yann (description and keywords), Dominique Papin (ie7 support), Mickaël Fradin (style), Loic Mathaud (title), Olivier Demah (auhor,generator)
* @copyright    2005-2006 Jouanneau laurent, 2007 Dominique Papin, 2008 Mickaël Fradin, 2009 Loic Mathaud, 2010 Olivier Demah
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_meta_html_html($tpl,$method,$param,$params=array())
{
	global $gJCoord,$gJConfig;
	if($gJCoord->response->getType()!='html'){
		return;
	}
	switch($method){
		case 'title':
			$gJCoord->response->title=$param;
			break;
		case 'js':
			$gJCoord->response->addJSLink($param,$params);
			break;
		case 'css':
			$gJCoord->response->addCSSLink($param,$params);
			break;
		case 'jsie':
			$gJCoord->response->addJSLink($param,$params,true);
			break;
		case 'cssie':
			$gJCoord->response->addCSSLink($param,$params,true);
			break;
		case 'cssie7':
			$gJCoord->response->addCSSLink($param,$params,'IE 7');
			break;
		case 'cssltie7':
			$gJCoord->response->addCSSLink($param,$params,'lt IE 7');
			break;
		case 'csstheme':
			$gJCoord->response->addCSSLink($gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/'.$param,$params);
			break;
		case 'cssthemeie':
			$gJCoord->response->addCSSLink($gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/'.$param,$params,true);
			break;
		case 'cssthemeie7':
			$gJCoord->response->addCSSLink($gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/'.$param,$params,'IE 7');
			break;
		case 'cssthemeltie7':
			$gJCoord->response->addCSSLink($gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/'.$param,$params,'lt IE 7');
			break;
		case 'style':
			if(is_array($param)){
				foreach($param as $p1=>$p2){
					$gJCoord->response->addStyle($p1,$p2);
				}
			}
			break;
		case 'bodyattr':
			if(is_array($param)){
				foreach($param as $p1=>$p2){
					if(!is_numeric($p1))$gJCoord->response->bodyTagAttributes[$p1]=$p2;
				}
			}
			break;
		case 'keywords':
			$gJCoord->response->addMetaKeywords($param);
			break;
		case 'description':
			$gJCoord->response->addMetaDescription($param);
			break;
		case 'others':
			$gJCoord->response->addHeadContent($param);
			break;
		case 'author':
			$gJCoord->response->addMetaAuthor($param);
			break;
		case 'generator':
			$gJCoord->response->addMetaGenerator($param);
			break;
	}
}
