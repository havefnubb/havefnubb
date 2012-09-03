<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright  2005-2012 Laurent Jouanneau
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_meta_xml_xml($tpl,$method,$param)
{
	$resp=jApp::coord()->response;
	if($resp->getFormatType()!='xml'){
		return;
	}
	switch($method){
		case 'xsl':
			$resp->addXSLStyleSheet($param);
			break;
		case 'css':
			$resp->addCSSLink($param);
			break;
		case 'csstheme':
			$resp->addCSSLink(jApp::config()->urlengine['basePath'].'themes/'.jApp::config()->theme.'/'.$param);
			break;
	}
}
