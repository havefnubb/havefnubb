<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  urls_engine
* @author      Laurent Jouanneau
* @contributor GeekBay
* @copyright   2005-2010 Laurent Jouanneau, 2010 Geekbay
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class simpleUrlEngine implements jIUrlEngine{
	protected $urlspe=null;
	protected $urlhttps=null;
	public function parseFromRequest($request,$params){
		return new jUrlAction($params,$request->type);
	}
	public function parse($scriptNamePath,$pathinfo,$params){
		return new jUrlAction($params);
	}
	public function create($urlact){
		global $gJConfig;
		$m=$urlact->getParam('module');
		$a=$urlact->getParam('action');
		$scriptName=$this->getBasePath($urlact->requestType,$m,$a);
		$scriptName.=$this->getScript($urlact->requestType,$m,$a);
		if(!$gJConfig->urlengine['multiview']){
			$scriptName.=$gJConfig->urlengine['entrypointExtension'];
		}
		$url=new jUrl($scriptName,$urlact->params,'');
		if(in_array($urlact->requestType,array('xmlrpc','jsonrpc','soap')))
		$url->clearParam();
		return $url;
	}
	protected function getBasePath($requestType,$module=null,$action=null){
		global $gJConfig;
		if($this->urlhttps==null){
			$this->urlhttps=array();
			$selectors=preg_split("/[\s,]+/",$gJConfig->urlengine['simple_urlengine_https']);
			foreach($selectors as $sel2){
				$this->urlhttps[$sel2]=true;
			}
		}
		$usehttps=false;
		if(count($this->urlhttps)){
		if($action&&isset($this->urlhttps[$module.'~'.$action.'@'.$requestType])){
			$usehttps=true;
		}elseif($module&&isset($this->urlhttps[$module.'~*@'.$requestType])){
			$usehttps=true;
		}elseif(isset($this->urlhttps['@'.$requestType])){
			$usehttps=true;
		}
		}
		if($usehttps)
		return 'https://'.$_SERVER['HTTP_HOST'].$gJConfig->urlengine['basePath'];
		else
		return $gJConfig->urlengine['basePath'];
	}
	protected function getScript($requestType,$module=null,$action=null){
		global $gJConfig;
		$script=$gJConfig->urlengine['defaultEntrypoint'];
		if(count($gJConfig->simple_urlengine_entrypoints)){
			if($this->urlspe==null){
				$this->urlspe=array();
				foreach($gJConfig->simple_urlengine_entrypoints as $entrypoint=>$sel){
					$selectors=preg_split("/[\s,]+/",$sel);
					foreach($selectors as $sel2){
						$this->urlspe[$sel2]=str_replace('__','/',$entrypoint);
					}
				}
			}
			if($action&&isset($this->urlspe[$s1=$module.'~'.$action.'@'.$requestType])){
				$script=$this->urlspe[$s1];
			}elseif($action&&isset($this->urlspe[$s1=$module.'~'.substr($action,0,strrpos($action,":")).':*@'.$requestType])){
				$script=$this->urlspe[$s1];
			}elseif($module&&isset($this->urlspe[$s2=$module.'~*@'.$requestType])){
				$script=$this->urlspe[$s2];
			}elseif(isset($this->urlspe[$s3='@'.$requestType])){
				$script=$this->urlspe[$s3];
			}
		}
		return $script;
	}
}
