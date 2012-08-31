<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  urls_engine
 * @author      Laurent Jouanneau
 * @copyright   2005-2011 Laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
class jSelectorUrlCfgSig extends jSelectorCfg{
	public $type='urlcfgsig';
	public function getCompiler(){
		require_once(dirname(__FILE__).'/jSignificantUrlsCompiler.class.php');
		$o=new jSignificantUrlsCompiler();
		return $o;
	}
	public function getCompiledFilePath(){return jApp::tempPath('compiled/urlsig/'.$this->file.'.creationinfos.php');}
}
class jSelectorUrlHandler extends jSelectorClass{
	public $type='urlhandler';
	protected $_suffix='.urlhandler.php';
	protected function _createPath(){
		global $gJConfig;
		if(isset($gJConfig->_modulesPathList[$this->module])){
			$p=$gJConfig->_modulesPathList[$this->module];
		}else if(isset($gJConfig->_externalModulesPathList[$this->module])){
			$p=$gJConfig->_externalModulesPathList[$this->module];
		}else{
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$this->_path=$p.$this->_dirname.$this->subpath.$this->className.$this->_suffix;
		if(!file_exists($this->_path)||strpos($this->subpath,'..')!==false){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),$this->type));
		}
	}
}
interface jIUrlSignificantHandler{
	public function parse($url);
	public function create($urlact,$url);
}
class significantUrlEngine implements jIUrlEngine{
	protected $dataCreateUrl=null;
	protected $dataParseUrl=null;
	public function parseFromRequest($request,$params){
		global $gJConfig;
		if($gJConfig->urlengine['enableParser']){
			$sel=new jSelectorUrlCfgSig($gJConfig->urlengine['significantFile']);
			jIncluder::inc($sel);
			$snp=$gJConfig->urlengine['urlScriptIdenc'];
			$file=jApp::tempPath('compiled/urlsig/'.$sel->file.'.'.$snp.'.entrypoint.php');
			if(file_exists($file)){
				require($file);
				$this->dataCreateUrl=& $GLOBALS['SIGNIFICANT_CREATEURL'];
				$this->dataParseUrl=& $GLOBALS['SIGNIFICANT_PARSEURL'][$snp];
				$isHttps=($request->getProtocol()=='https://');
				return $this->_parse($request->urlScript,$request->urlPathInfo,$params,$isHttps);
			}
		}
		$urlact=new jUrlAction($params);
		return $urlact;
	}
	public function parse($scriptNamePath,$pathinfo,$params){
		global $gJConfig;
		if($gJConfig->urlengine['enableParser']){
			$sel=new jSelectorUrlCfgSig($gJConfig->urlengine['significantFile']);
			jIncluder::inc($sel);
			$basepath=$gJConfig->urlengine['basePath'];
			if(strpos($scriptNamePath,$basepath)===0){
				$snp=substr($scriptNamePath,strlen($basepath));
			}
			else{
				$snp=$scriptNamePath;
			}
			$pos=strrpos($snp,$gJConfig->urlengine['entrypointExtension']);
			if($pos!==false){
				$snp=substr($snp,0,$pos);
			}
			$snp=rawurlencode($snp);
			$file=jApp::tempPath('compiled/urlsig/'.$sel->file.'.'.$snp.'.entrypoint.php');
			if(file_exists($file)){
				require($file);
				$this->dataCreateUrl=& $GLOBALS['SIGNIFICANT_CREATEURL'];
				$this->dataParseUrl=& $GLOBALS['SIGNIFICANT_PARSEURL'][$snp];
				return $this->_parse($scriptNamePath,$pathinfo,$params,false);
			}
		}
		$urlact=new jUrlAction($params);
		return $urlact;
	}
	protected function _parse($scriptNamePath,$pathinfo,$params,$isHttps){
		global $gJConfig;
		$urlact=null;
		$isDefault=false;
		$url=new jUrl($scriptNamePath,$params,$pathinfo);
		foreach($this->dataParseUrl as $k=>$infoparsing){
			if($k==0){
				$isDefault=$infoparsing;
				continue;
			}
			if(count($infoparsing)< 7){
				list($module,$action,$reg,$selectorHandler,$secondariesActions,$needHttps)=$infoparsing;
				$url2=clone $url;
				if($reg!=''){
					if(preg_match($reg,$pathinfo,$m))
						$url2->pathInfo=isset($m[1])?$m[1]:'/';
					else
						continue;
				}
				$s=new jSelectorUrlHandler($selectorHandler);
				include_once($s->getPath());
				$c=$s->className.'UrlsHandler';
				$handler=new $c();
				$url2->params['module']=$module;
				if($secondariesActions&&isset($params['action'])){
					if(strpos($params['action'],':')===false){
						$params['action']='default:'.$params['action'];
					}
					if(in_array($params['action'],$secondariesActions))
						$url2->params['action']=$params['action'];
					else
						$url2->params['action']=$action;
				}
				else{
					$url2->params['action']=$action;
				}
				if($urlact=$handler->parse($url2)){
					break;
				}
			}
			elseif(preg_match($infoparsing[2],$pathinfo,$matches)){
				list($module,$action,$reg,$dynamicValues,$escapes,
					$staticValues,$secondariesActions,$needHttps)=$infoparsing;
				if(isset($params['module'])&&$params['module']!==$module)
					continue;
				if($module!='')
					$params['module']=$module;
				if($secondariesActions&&isset($params['action'])){
					if(strpos($params['action'],':')===false){
						$params['action']='default:'.$params['action'];
					}
					if(!in_array($params['action'],$secondariesActions)&&$action!=''){
						$params['action']=$action;
					}
				}
				else{
					if($action!='')
						$params['action']=$action;
				}
				if($staticValues){
					$params=array_merge($params,$staticValues);
				}
				if(count($matches)){
					array_shift($matches);
					foreach($dynamicValues as $k=>$name){
						if(isset($matches[$k])){
							if($escapes[$k]==2){
								$params[$name]=jUrl::unescape($matches[$k]);
							}
							else{
								$params[$name]=$matches[$k];
							}
						}
					}
				}
				$urlact=new jUrlAction($params);
				break;
			}
		}
		if(!$urlact){
			if($isDefault&&$pathinfo==''){
				$urlact=new jUrlAction($params);
			}
			else{
				try{
					$urlact=jUrl::get($gJConfig->urlengine['notfoundAct'],array(),jUrl::JURLACTION);
				}
				catch(Exception $e){
					$urlact=new jUrlAction(array('module'=>'jelix','action'=>'error:notfound'));
				}
			}
		}
		else if($needHttps&&! $isHttps){
			$urlact=new jUrlAction(array('module'=>'jelix','action'=>'error:notfound'));
		}
		return $urlact;
	}
	public function create($urlact){
		if($this->dataCreateUrl==null){
			$sel=new jSelectorUrlCfgSig($GLOBALS['gJConfig']->urlengine['significantFile']);
			jIncluder::inc($sel);
			$this->dataCreateUrl=& $GLOBALS['SIGNIFICANT_CREATEURL'];
		}
		$url=new jUrl('',$urlact->params,'');
		$module=$url->getParam('module',jContext::get());
		$action=$url->getParam('action');
		$id=$module.'~'.$action.'@'.$urlact->requestType;
		$urlinfo=null;
		if(isset($this->dataCreateUrl [$id])){
			$urlinfo=$this->dataCreateUrl[$id];
			$url->delParam('module');
			$url->delParam('action');
		}
		else{
			$id=$module.'~*@'.$urlact->requestType;
			if(isset($this->dataCreateUrl[$id])){
				$urlinfo=$this->dataCreateUrl[$id];
				if($urlinfo[0]!=3||$urlinfo[3]===true)
					$url->delParam('module');
			}
			else{
				$id='@'.$urlact->requestType;
				if(isset($this->dataCreateUrl [$id])){
					$urlinfo=$this->dataCreateUrl[$id];
				}
				else{
					throw new Exception("Significant url engine doesn't find corresponding url to this action :".$module.'~'.$action.'@'.$urlact->requestType);
				}
			}
		}
		if($urlinfo[0]==4){
			$l=count($urlinfo);
			$urlinfofound=null;
			for($i=1;$i < $l;$i++){
				$ok=true;
				foreach($urlinfo[$i][7] as $n=>$v){
					if($url->getParam($n,'')!=$v){
						$ok=false;
						break;
					}
				}
				if($ok){
					$urlinfofound=$urlinfo[$i];
					break;
				}
			}
			if($urlinfofound!==null){
				$urlinfo=$urlinfofound;
			}
			else{
				$urlinfo=$urlinfo[1];
			}
		}
		$url->scriptName=$GLOBALS['gJConfig']->urlengine['basePath'].$urlinfo[1];
		if($urlinfo[2])
			$url->scriptName=$GLOBALS['gJCoord']->request->getServerURI(true).$url->scriptName;
		if($urlinfo[1]&&!$GLOBALS['gJConfig']->urlengine['multiview']){
			$url->scriptName.=$GLOBALS['gJConfig']->urlengine['entrypointExtension'];
		}
		if(in_array($urlact->requestType,array('xmlrpc','jsonrpc','soap'))){
			$url->clearParam();
			return $url;
		}
		if($urlinfo[0]==0){
			$s=new jSelectorUrlHandler($urlinfo[3]);
			$c=$s->resource.'UrlsHandler';
			$handler=new $c();
			$handler->create($urlact,$url);
			if($urlinfo[4]!=''){
				$url->pathInfo=$urlinfo[4].$url->pathInfo;
			}
		}
		elseif($urlinfo[0]==1){
			$pi=$urlinfo[5];
			foreach($urlinfo[3] as $k=>$param){
				switch($urlinfo[4][$k]){
					case 2:
						$value=jUrl::escape($url->getParam($param,''),true);
						break;
					case 1:
						$value=str_replace('%2F','/',urlencode($url->getParam($param,'')));
						break;
					default:
						$value=urlencode($url->getParam($param,''));
						break;
				}
				$pi=str_replace(':'.$param,$value,$pi);
				$url->delParam($param);
			}
			$url->pathInfo=$pi;
			if($urlinfo[6])
				$url->setParam('action',$action);
			foreach($urlinfo[7] as $name=>$value){
				$url->delParam($name);
			}
		}
		elseif($urlinfo[0]==3){
			if($urlinfo[3]){
				$url->delParam('module');
			}
		}
		return $url;
	}
}
