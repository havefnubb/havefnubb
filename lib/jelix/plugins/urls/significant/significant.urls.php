<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  urls_engine
 * @author      Laurent Jouanneau
 * @copyright   2005-2012 Laurent Jouanneau
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
		$conf=jApp::config();
		if(isset($conf->_modulesPathList[$this->module])){
			$p=$conf->_modulesPathList[$this->module];
		}else if(isset($conf->_externalModulesPathList[$this->module])){
			$p=$conf->_externalModulesPathList[$this->module];
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
		$conf=& jApp::config()->urlengine;
		if($conf['enableParser']){
			$sel=new jSelectorUrlCfgSig($conf['significantFile']);
			jIncluder::inc($sel);
			$snp=$conf['urlScriptIdenc'];
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
		$conf=& jApp::config()->urlengine;
		if($conf['enableParser']){
			$sel=new jSelectorUrlCfgSig($conf['significantFile']);
			jIncluder::inc($sel);
			$basepath=$conf['basePath'];
			if(strpos($scriptNamePath,$basepath)===0){
				$snp=substr($scriptNamePath,strlen($basepath));
			}
			else{
				$snp=$scriptNamePath;
			}
			$pos=strrpos($snp,$conf['entrypointExtension']);
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
					foreach($staticValues as $n=>$v){
						if($v[0]=='$'){
							$typeStatic=$v[1];
							$v=substr($v,2);
							if($typeStatic=='l')
								jApp::config()->locale=jLocale::langToLocale($v);
							else if($typeStatic=='L')
								jApp::config()->locale=$v;
						}
						$params[$n]=$v;
					}
				}
				if(count($matches)){
					array_shift($matches);
					foreach($dynamicValues as $k=>$name){
						if(isset($matches[$k])){
							if($escapes[$k] & 2){
								$params[$name]=jUrl::unescape($matches[$k]);
							}
							else{
								$params[$name]=$matches[$k];
								if($escapes[$k] & 4){
									$v=$matches[$k];
									if(preg_match('/^\w{2,3}$/',$v,$m))
										jApp::config()->locale=jLocale::langToLocale($v);
									else{
										jApp::config()->locale=$v;
										$params[$name]=substr($v,0,strpos('_'));
									}
								}
								else if($escapes[$k] & 8){
									$v=$matches[$k];
									if(preg_match('/^\w{2,3}$/',$v,$m)){
										jApp::config()->locale=$params[$name]=jLocale::langToLocale($v);
									}
									else
										jApp::config()->locale=$v;
								}
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
					$urlact=jUrl::get(jApp::config()->urlengine['notfoundAct'],array(),jUrl::JURLACTION);
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
			$sel=new jSelectorUrlCfgSig(jApp::config()->urlengine['significantFile']);
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
					$specialStatic=($v[0]=='$');
					$paramStatic=$url->getParam($n,null);
					if($specialStatic){
						$typePS=$v[1];
						$v=substr($v,2);
						if($typePS=='l'){
							if($paramStatic===null)
								$paramStatic=jLocale::getCurrentLang();
							else if(preg_match('/^(\w{2,3})_\w{2,3}$/',$paramStatic,$m)){
								$paramStatic=$m[1];
							}
						}
						elseif($typePS=='L'){
							if($paramStatic===null)
								$paramStatic=jApp::config()->locale;
							else if(preg_match('/^\w{2,3}$/',$paramStatic,$m)){
								$paramStatic=jLocale::langToLocale($paramStatic);
							}
						}
					}
					if($paramStatic!=$v){
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
		$url->scriptName=jApp::config()->urlengine['basePath'].$urlinfo[1];
		if($urlinfo[2])
			$url->scriptName=jApp::coord()->request->getServerURI(true).$url->scriptName;
		if($urlinfo[1]&&!jApp::config()->urlengine['multiview']){
			$url->scriptName.=jApp::config()->urlengine['entrypointExtension'];
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
				$typeParam=$urlinfo[4][$k];
				$value=$url->getParam($param,'');
				if($typeParam & 2){
					$value=jUrl::escape($value,true);
				}
				else if($typeParam & 1){
					$value=str_replace('%2F','/',urlencode($value));
				}
				else if($typeParam & 4){
					if($value==''){
						$value=jLocale::getCurrentLang();
					}
					else if(preg_match('/^(\w{2,3})_\w{2,3}$/',$value,$m)){
						$value=$m[1];
					}
				}
				else if($typeParam & 8){
					if($value==''){
						$value=jApp::config()->locale;
					}
					else if(preg_match('/^\w{2,3}$/',$value,$m)){
						$value=jLocale::langToLocale($value);
					}
				}
				else{
					$value=urlencode($value);
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
