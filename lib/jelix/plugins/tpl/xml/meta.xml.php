<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Jouanneau Laurent
* @copyright  2005-2008 Jouanneau laurent
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_meta_xml_xml($tpl, $method, $param)
{
	global $gJCoord, $gJConfig;
	if($gJCoord->response->getFormatType() != 'xml'){
		return;
	}
	switch($method){
		case 'xsl':
			$gJCoord->response->addXSLStyleSheet($param);
			break;
		case 'css':
			$gJCoord->response->addCSSLink($param);
			break;
		case 'csstheme':
			$gJCoord->response->addCSSLink($gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/'.$param);
			break;
	}
}