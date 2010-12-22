<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  urls_engine
* @author      Laurent Jouanneau
* @copyright   2005-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(dirname(__FILE__).'/../simple/simple.urls.php');
class basic_significantUrlEngine extends simpleUrlEngine{
	function __construct(){
		global $gJConfig;
		foreach($gJConfig->basic_significant_urlengine_entrypoints as $script=>$val){
			if(strpos($script,'__')!==false){
				$script=str_replace('__','/',$script);
				$gJConfig->basic_significant_urlengine_entrypoints[$script]=$val;
			}
		}
	}
	public function parseFromRequest($request,$params){
		return $this->parse($request->urlScript,$request->urlPathInfo,$params);
	}
	public function parse($scriptNamePath,$pathinfo,$params){
		global $gJConfig;
		if($gJConfig->urlengine['enableParser']){
			$pathinfo=trim($pathinfo,'/');
			if($pathinfo!=''){
				$list=explode('/',$pathinfo);
				$co=count($list);
				if($co==1){
					$params['module']=$list[0];
					$params['action']='default:index';
				}
				else if($co==2){
					$params['module']=$list[0];
					$params['action']=$list[1].':index';
				}
				else{
					$params['module']=$list[0];
					$params['action']=$list[1].':'.$list[2];
				}
			}
		}
		return new jUrlAction($params);
	}
	public function create($urlact){
		global $gJConfig;
		$m=$urlact->getParam('module');
		$a=$urlact->getParam('action');
		$scriptName=$this->getBasePath($urlact->requestType,$m,$a);
		$script=$this->getScript($urlact->requestType,$m,$a);
		if(isset($gJConfig->basic_significant_urlengine_entrypoints[$script])
			&&$gJConfig->basic_significant_urlengine_entrypoints[$script]){
			if(!$gJConfig->urlengine['multiview']){
				$script.=$gJConfig->urlengine['entrypointExtension'];
			}
			$scriptName.=$script;
		}
		$url=new jUrl($scriptName,$urlact->params,'');
		if(in_array($urlact->requestType,array('xmlrpc','jsonrpc','soap'))){
			$url->clearParam();
		}else{
			$pi='/'.$m.'/';
			if($a!='default:index'){
				list($c,$a)=explode(':',$a);
				$pi.=$c.'/';
				if($a!='index')
					$pi.=$a;
			}
			$url->pathInfo=$pi;
			$url->delParam('module');
			$url->delParam('action');
		}
		return $url;
	}
}
