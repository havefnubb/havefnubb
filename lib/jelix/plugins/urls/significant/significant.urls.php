<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  urls_engine
 * @author      Laurent Jouanneau
 * @contributor
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
class jSelectorUrlCfgSig extends jSelectorCfg{
	public $type = 'urlcfgsig';
	public function getCompiler(){
		require_once(dirname(__FILE__).'/jSignificantUrlsCompiler.class.php');
		$o = new jSignificantUrlsCompiler();
		return $o;
	}
	public function getCompiledFilePath(){ return JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$this->file.'.creationinfos.php';}
}
class jSelectorUrlHandler extends jSelectorClass{
	public $type = 'urlhandler';
	protected $_suffix = '.urlhandler.php';
}
interface jIUrlSignificantHandler{
	public function parse($url);
	public function create($urlact, $url);
}
class significantUrlEngine implements jIUrlEngine{
	protected $dataCreateUrl = null;
	protected $dataParseUrl =  null;
	public function parseFromRequest($request, $params){
		global $gJConfig;
		if($gJConfig->urlengine['enableParser']){
			$sel = new jSelectorUrlCfgSig($gJConfig->urlengine['significantFile']);
			jIncluder::inc($sel);
			$snp = $gJConfig->urlengine['urlScriptIdenc'];
			$file=JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$sel->file.'.'.$snp.'.entrypoint.php';
			if(file_exists($file)){
				require($file);
				$this->dataCreateUrl = & $GLOBALS['SIGNIFICANT_CREATEURL'];
				$this->dataParseUrl = & $GLOBALS['SIGNIFICANT_PARSEURL'][$snp];
				return $this->_parse($request->urlScript, $request->urlPathInfo, $params);
			}
		}
		$urlact = new jUrlAction($params);
		return $urlact;
	}
	public function parse($scriptNamePath, $pathinfo, $params){
		global $gJConfig;
		if($gJConfig->urlengine['enableParser']){
			$sel = new jSelectorUrlCfgSig($gJConfig->urlengine['significantFile']);
			jIncluder::inc($sel);
			$basepath = $gJConfig->urlengine['basePath'];
			if(strpos($scriptNamePath, $basepath) === 0){
				$snp = substr($scriptNamePath,strlen($basepath));
			}else{
				$snp = $scriptNamePath;
			}
			$pos = strrpos($snp,$gJConfig->urlengine['entrypointExtension']);
			if($pos !== false){
				$snp = substr($snp,0,$pos);
			}
			$snp = rawurlencode($snp);
			$file=JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$sel->file.'.'.$snp.'.entrypoint.php';
			if(file_exists($file)){
				require($file);
				$this->dataCreateUrl = & $GLOBALS['SIGNIFICANT_CREATEURL'];
				$this->dataParseUrl = & $GLOBALS['SIGNIFICANT_PARSEURL'][$snp];
				return $this->_parse($scriptNamePath, $pathinfo, $params);
			}
		}
		$urlact = new jUrlAction($params);
		return $urlact;
	}
	protected function _parse($scriptNamePath, $pathinfo, $params){
		global $gJConfig;
		$urlact = null;
		$isDefault = false;
		$url = new jUrl($scriptNamePath, $params, $pathinfo);
		foreach($this->dataParseUrl as $k=>$infoparsing){
			if($k==0){
				$isDefault=$infoparsing;
				continue;
			}
			if(count($infoparsing) < 5){
				$s = new jSelectorUrlHandler($infoparsing[2]);
				$c =$s->className.'UrlsHandler';
				$handler =new $c();
				$url->params['module']=$infoparsing[0];
				if($infoparsing[3] && isset($params['action'])){
					if(strpos($params['action'], ':') === false){
						$params['action'] = 'default:'.$params['action'];
					}
					if(in_array($params['action'], $infoparsing[3]))
						$url->params['action']=$params['action'];
					else
						$url->params['action']=$infoparsing[1];
				}else{
					$url->params['action']=$infoparsing[1];
				}
				if($urlact = $handler->parse($url)){
					break;
				}
			}else{
				if(preg_match($infoparsing[2], $pathinfo, $matches)){
					if($infoparsing[0] !='')
						$params['module']=$infoparsing[0];
					if($infoparsing[6] && isset($params['action'])){
						if(strpos($params['action'], ':') === false){
							$params['action'] = 'default:'.$params['action'];
						}
						if(!in_array($params['action'], $infoparsing[6]) && $infoparsing[1] !=''){
							$params['action']=$infoparsing[1];
						}
					} else{
						if($infoparsing[1] !='')
							$params['action']=$infoparsing[1];
					}
					if($infoparsing[5]){
						$params = array_merge($params, $infoparsing[5]);
					}
					if(count($matches)){
						array_shift($matches);
						foreach($infoparsing[3] as $k=>$name){
							if(isset($matches[$k])){
								if($infoparsing[4][$k]){
									$params[$name] = jUrl::unescape($matches[$k]);
								}else{
									$params[$name] = $matches[$k];
								}
							}
						}
					}
					$urlact = new jUrlAction($params);
					break;
				}
			}
		}
		if(!$urlact){
			if($isDefault && $pathinfo == ''){
			   $urlact = new jUrlAction($params);
			} else{
			   try{
				   $urlact = jUrl::get($gJConfig->urlengine['notfoundAct'],array(),jUrl::JURLACTION);
			   }catch(Exception $e){
				   $urlact = new jUrlAction(array('module'=>'jelix', 'action'=>'error:notfound'));
			   }
			}
		}
		return $urlact;
	}
	public function create( $urlact){
		if($this->dataCreateUrl == null){
			$sel = new jSelectorUrlCfgSig($GLOBALS['gJConfig']->urlengine['significantFile']);
			jIncluder::inc($sel);
			$this->dataCreateUrl = & $GLOBALS['SIGNIFICANT_CREATEURL'];
		}
		$url = new jUrl('',$urlact->params,'');
		$module = $url->getParam('module', jContext::get());
		$action = $url->getParam('action');
		$id = $module.'~'.$action.'@'.$urlact->requestType;
		$urlinfo = null;
		if(isset($this->dataCreateUrl [$id])){
			$urlinfo = $this->dataCreateUrl[$id];
			$url->delParam('module');
			$url->delParam('action');
		}else{
			$id = $module.'~*@'.$urlact->requestType;
			if(isset($this->dataCreateUrl [$id])){
				$urlinfo = $this->dataCreateUrl[$id];
				$url->delParam('module');
			}else{
				$id = '@'.$urlact->requestType;
				if(isset($this->dataCreateUrl [$id])){
					$urlinfo = $this->dataCreateUrl[$id];
				}else{
					throw new Exception("Significant url engine doesn't find corresponding url to this action :".$module.'~'.$action.'@'.$urlact->requestType);
				}
			}
		}
		if($urlinfo[0]==4){
			$l = count($urlinfo);
			$urlinfofound = null;
			for($i=1; $i < $l; $i++){
				$ok = true;
				foreach($urlinfo[$i][7] as $n=>$v){
					if($url->getParam($n,'') != $v){
						$ok = false;
						break;
					}
				}
				if($ok){
					$urlinfofound = $urlinfo[$i];
					break;
				}
			}
			if($urlinfofound !== null){
				$urlinfo = $urlinfofound;
			}else{
				$urlinfo = $urlinfo[1];
			}
		}
		$url->scriptName = $GLOBALS['gJConfig']->urlengine['basePath'].$urlinfo[1];
		if($urlinfo[2])
			$url->scriptName = 'https://'.$_SERVER['HTTP_HOST'].$url->scriptName;
		if($urlinfo[1] && !$GLOBALS['gJConfig']->urlengine['multiview']){
			$url->scriptName.=$GLOBALS['gJConfig']->urlengine['entrypointExtension'];
		}
		if(in_array($urlact->requestType ,array('xmlrpc','jsonrpc','soap'))){
			$url->clearParam();
			return $url;
		}
		if($urlinfo[0]==0){
			$s = new jSelectorUrlHandler($urlinfo[3]);
			$c =$s->resource.'UrlsHandler';
			$handler =new $c();
			$handler->create($urlact, $url);
		}elseif($urlinfo[0]==1){
			$pi = $urlinfo[5];
			foreach($urlinfo[3] as $k=>$param){
				if($urlinfo[4][$k]){
					$pi=str_replace(':'.$param, jUrl::escape($url->getParam($param,''),true), $pi);
				}else{
					$pi=str_replace(':'.$param, $url->getParam($param,''), $pi);
				}
				$url->delParam($param);
			}
			$url->pathInfo = $pi;
			if($urlinfo[6])
				$url->setParam('action',$action);
			foreach($urlinfo[7] as $name=>$value){
				$url->delParam($name);
			}
		}elseif($urlinfo[0]==3){
			$url->delParam('module');
		}
		return $url;
	}
}