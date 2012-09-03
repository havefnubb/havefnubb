<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  responsehtml_plugin
* @author      Laurent Jouanneau
* @copyright   2010-2012 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class minifyHTMLResponsePlugin implements jIHTMLResponsePlugin{
	protected $response=null;
	protected $excludeCSS=array();
	protected $excludeJS=array();
	public function __construct(jResponse $c){
		$this->response=$c;
	}
	public function afterAction(){
	}
	public function beforeOutput(){
		if(!($this->response instanceof jResponseHtml))
			return;
		$conf=&jApp::config()->jResponseHtml;
		if($conf['minifyCSS']){
			if($conf['minifyExcludeCSS']){
				$this->excludeCSS=explode(',',$conf['minifyExcludeCSS']);
			}
			$this->response->setCSSLinks($this->generateMinifyList($this->response->getCSSLinks(),'excludeCSS'));
			$this->response->setCSSIELinks($this->generateMinifyList($this->response->getCSSIELinks(),'excludeCSS'));
		}
		if($conf['minifyJS']){
			if($conf['minifyExcludeJS']){
				$this->excludeJS=explode(',',$conf['minifyExcludeJS']);
			}
			$this->response->setJSLinks($this->generateMinifyList($this->response->getJSLinks(),'excludeJS'));
			$this->response->setJSIELinks($this->generateMinifyList($this->response->getJSIELinks(),'excludeJS'));
		}
	}
	public function atBottom(){
	}
	public function beforeOutputError(){
	}
	protected function generateMinifyList($list,$exclude){
		$pendingList=array();
		$pendingParameters=false;
		$resultList=array();
		foreach($list as $url=>$parameters){
			$pathAbsolute=(strpos($url,'http://')!==false);
			if($pathAbsolute||in_array($url,$this->$exclude)){
				$resultList[$url]=$parameters;
				continue;
			}
			ksort($parameters);
			if($pendingParameters===false){
				$pendingParameters=$parameters;
				$pendingList[]=$url;
				continue;
			}
			if($pendingParameters==$parameters){
				$pendingList[]=$url;
			}
			else{
				$resultList[$this->generateMinifyUrl($pendingList)]=$pendingParameters;
				$pendingList=array($url);
				$pendingParameters=$parameters;
			}
		}
		if($pendingParameters!==false&&count($pendingList)){
			$resultList[$this->generateMinifyUrl($pendingList)]=$pendingParameters;
		}
		return $resultList;
	}
	protected function generateMinifyUrl($urlsList){
		$url=jApp::config()->urlengine['basePath'].jApp::config()->jResponseHtml['minifyEntryPoint'].'?f=';
		$url.=implode(',',$urlsList);
		return $url;
	}
}
