<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  urls_engine
* @author      Laurent Jouanneau
* @contributor Thibault Piront (nuKs)
* @copyright   2005-2010 Laurent Jouanneau
* @copyright   2007 Thibault Piront
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class significantUrlInfoParsing{
	public $entryPoint='';
	public $entryPointUrl='';
	public $isHttps=false;
	public $isDefault=false;
	public $action='';
	public $module='';
	public $actionOverride=false;
	public $requestType='';
	public $statics=array();
	public $params=array();
	public $escapes=array();
	function __construct($rt,$ep,$isDefault,$isHttps){
		$this->requestType=$rt;
		$this->entryPoint=$this->entryPointUrl=$ep;
		$this->isDefault=$isDefault;
		$this->isHttps=$isHttps;
	}
	function getFullSel(){
		if($this->action){
			$act=$this->action;
			if(substr($act,-1)==':')
				$act.='index';
		}
		else
			$act='*';
		return $this->module.'~'.$act.'@'.$this->requestType;
	}
}
class jSignificantUrlsCompiler implements jISimpleCompiler{
	protected $requestType;
	protected $defaultUrl;
	protected $parseInfos;
	protected $createUrlInfos;
	protected $createUrlContent;
	protected $typeparam=array('string'=>'([^\/]+)','char'=>'([^\/])','letter'=>'(\w)',
		'number'=>'(\d+)','int'=>'(\d+)','integer'=>'(\d+)','digit'=>'(\d)',
		'date'=>'([0-2]\d{3}\-(?:0[1-9]|1[0-2])\-(?:[0-2][1-9]|3[0-1]))',
		'year'=>'([0-2]\d{3})','month'=>'(0[1-9]|1[0-2])','day'=>'([0-2][1-9]|[1-2]0|3[0-1])'
		);
	public function compile($aSelector){
		$sourceFile=$aSelector->getPath();
		$cachefile=$aSelector->getCompiledFilePath();
		$xml=simplexml_load_file($sourceFile);
		if(!$xml){
			return false;
		}
		$this->createUrlInfos=array();
		$this->createUrlContent="<?php \n";
		$this->readProjectXml();
		$this->retrieveModulePaths(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
		foreach($xml->children()as $tagname=>$tag){
			if(!preg_match("/^(.*)entrypoint$/",$tagname,$m)){
				continue;
			}
			$type=$m[1];
			if($type==''){
				if(isset($tag['type']))
					$type=(string)$tag['type'];
				if($type=='')
					$type='classic';
			}
			$this->defaultUrl=new significantUrlInfoParsing(
				$type,
				(string)$tag['name'],
				(isset($tag['default'])?(((string)$tag['default'])=='true'):false),
				(isset($tag['https'])?(((string)$tag['https'])=='true'):false)
			);
			if(isset($tag['noentrypoint'])&&(string)$tag['noentrypoint']=='true')
				$this->defaultUrl->entryPointUrl='';
			$optionalTrailingSlash=(isset($tag['optionalTrailingSlash'])&&$tag['optionalTrailingSlash']=='true');
			$this->parseInfos=array($this->defaultUrl->isDefault);
			$this->retrieveModulePaths($this->getEntryPointConfig($this->defaultUrl->entryPoint));
			if($this->defaultUrl->isDefault){
				$this->createUrlInfos['@'.$this->defaultUrl->requestType]=array(2,$this->defaultUrl->entryPoint,$this->defaultUrl->isHttps);
			}
			$createUrlInfosDedicatedModules=array();
			$parseContent="<?php \n";
			foreach($tag->children()as $tagname=>$url){
				$u=clone $this->defaultUrl;
				$u->module=(string)$url['module'];
				if(isset($url['https'])){
					$u->isHttps=(((string)$url['https'])=='true');
				}
				if(isset($url['noentrypoint'])&&((string)$url['noentrypoint'])=='true'){
					$u->entryPointUrl='';
				}
				if(isset($url['include'])){
					$this->readInclude($url,$u);
					continue;
				}
				if(!$u->isDefault&&!isset($url['action'])&&!isset($url['handler'])){
					$this->parseInfos[]=array($u->module,'','/.*/',array(),array(),array(),false);
					$createUrlInfosDedicatedModules[$u->getFullSel()]=array(3,$u->entryPointUrl,$u->isHttps,true);
					continue;
				}
				$u->action=(string)$url['action'];
				if(strpos($u->action,':')===false){
					$u->action='default:'.$u->action;
				}
				if(isset($url['actionoverride'])){
					$u->actionOverride=preg_split("/[\s,]+/",(string)$url['actionoverride']);
					foreach($u->actionOverride as &$each){
						if(strpos($each,':')===false){
							$each='default:'.$each;
						}
					}
				}
				if(isset($url['handler'])){
					$this->newHandler($u,$url);
					continue;
				}
				if(isset($url['pathinfo'])){
					$path=(string)$url['pathinfo'];
					$regexppath=$this->extractDynamicParams($url,$path,$u);
				}
				else{
					$regexppath='.*';
					$path='';
				}
				$tempOptionalTrailingSlash=$optionalTrailingSlash;
				if(isset($url['optionalTrailingSlash'])){
					$tempOptionalTrailingSlash=($url['optionalTrailingSlash']=='true');
				}
				if($tempOptionalTrailingSlash){
					if(substr($regexppath,-1)=='/'){
						$regexppath.='?';
					}
					else{
						$regexppath.='\/?';
					}
				}
				foreach($url->static as $var){
					$u->statics[(string)$var['name']]=(string)$var['value'];
				}
				$this->parseInfos[]=array($u->module,$u->action,'!^'.$regexppath.'$!',$u->params,$u->escapes,$u->statics,$u->actionOverride);
				$this->appendUrlInfo($u,$path,false);
				if($u->actionOverride){
					foreach($u->actionOverride as $ao){
						$u->action=$ao;
						$this->appendUrlInfo($u,$path,true);
					}
				}
			}
			$c=count($createUrlInfosDedicatedModules);
			foreach($createUrlInfosDedicatedModules as $k=>$inf){
				if($c > 1)
					$inf[3]=false;
				$this->createUrlInfos[$k]=$inf;
			}
			$parseContent.='$GLOBALS[\'SIGNIFICANT_PARSEURL\'][\''.rawurlencode($this->defaultUrl->entryPoint).'\'] = '
							.var_export($this->parseInfos,true).";\n?>";
			jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$aSelector->file.'.'.rawurlencode($this->defaultUrl->entryPoint).'.entrypoint.php',$parseContent);
		}
		$this->createUrlContent.='$GLOBALS[\'SIGNIFICANT_CREATEURL\'] ='.var_export($this->createUrlInfos,true).";\n?>";
		jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$aSelector->file.'.creationinfos.php',$this->createUrlContent);
		return true;
	}
	protected function readProjectXml(){
		$xml=simplexml_load_file(JELIX_APP_PATH.'project.xml');
		foreach($xml->entrypoints->entry as $entrypoint){
			$file=(string)$entrypoint['file'];
			if(substr($file,-4)!='.php')
				$file.='.php';
			$configFile=(string)$entrypoint['config'];
			$this->entryPoints[$file]=$configFile;
		}
	}
	protected function getEntryPointConfig($entrypoint){
		if(substr($entrypoint,-4)!='.php')
			$entrypoint.='.php';
		if(!isset($this->entryPoints[$entrypoint]))
			throw new Exception('The entry point "'.$entrypoint.'" is not declared into project.xml');
		return JELIX_APP_CONFIG_PATH.$this->entryPoints[$entrypoint];
	}
	protected $entryPoints=array();
	protected $modulesRepositories=array();
	protected $modulesPath=array();
	protected function retrieveModulePaths($configFile){
		$conf=parse_ini_file($configFile);
		if(!array_key_exists('modulesPath',$conf))
			return;
		$list=preg_split('/ *, */',$conf['modulesPath']);
		array_unshift($list,JELIX_LIB_PATH.'core-modules/');
		foreach($list as $k=>$path){
			if(trim($path)=='')continue;
			$p=str_replace(array('lib:','app:'),array(LIB_PATH,JELIX_APP_PATH),$path);
			if(!file_exists($p)){
				continue;
			}
			if(substr($p,-1)!='/')
				$p.='/';
			if(isset($this->modulesRepositories[$p]))
				continue;
			$this->modulesRepositories[$p]=true;
			if($handle=opendir($p)){
				while(false!==($f=readdir($handle))){
					if($f[0]!='.'&&is_dir($p.$f)){
						$this->modulesPath[$f]=$p.$f.'/';
					}
				}
				closedir($handle);
			}
		}
	}
	protected function newHandler($u,$url,$pathinfo=''){
		$class=(string)$url['handler'];
		$p=strpos($class,'~');
		if($p===false)
			$selclass=$u->module.'~'.$class;
		elseif($p==0)
			$selclass=$u->module.$class;
		else
			$selclass=$class;
		$s=new jSelectorUrlHandler($selclass);
		if(!isset($url['action'])){
			$u->action='*';
		}
		$regexp='';
		if(isset($url['pathinfo'])){
			$pathinfo.='/'.trim((string)$url['pathinfo'],'/');
		}
		if($pathinfo!='/'){
			$regexp='!^'.preg_quote($pathinfo,'!').'(/.*)?$!';
		}
		$this->createUrlContent.="include_once('".$s->getPath()."');\n";
		$this->parseInfos[]=array($u->module,$u->action,$regexp,$selclass,$u->actionOverride);
		$this->createUrlInfos[$u->getFullSel()]=array(0,$u->entryPointUrl,$u->isHttps,$selclass,$pathinfo);
		if($u->actionOverride){
			foreach($u->actionOverride as $ao){
				$u->action=$ao;
				$this->createUrlInfos[$u->getFullSel()]=array(0,$u->entryPointUrl,$u->isHttps,$selclass,$pathinfo);
			}
		}
	}
	protected function extractDynamicParams($url,$regexppath,$u){
		$regexppath=preg_quote($regexppath,'!');
		if(preg_match_all("/\\\:([a-zA-Z_]+)/",$regexppath,$m,PREG_PATTERN_ORDER)){
			$u->params=$m[1];
			foreach($url->param as $var){
				$name=(string) $var['name'];
				$k=array_search($name,$u->params);
				if($k===false){
					continue;
				}
				if(isset($var['escape'])){
					$u->escapes[$k]=(((string)$var['escape'])=='true');
				}
				else{
					$u->escapes[$k]=false;
				}
				if(isset($var['type'])){
					if(isset($this->typeparam[(string)$var['type']]))
						$regexp=$this->typeparam[(string)$var['type']];
					else
						$regexp='([^\/]+)';
				}
				elseif(isset($var['regexp'])){
					$regexp='('.(string)$var['regexp'].')';
				}
				else{
					$regexp='([^\/]+)';
				}
				$regexppath=str_replace('\:'.$name,$regexp,$regexppath);
			}
			foreach($u->params as $k=>$name){
				if(isset($u->escapes[$k])){
					continue;
				}
				$u->escapes[$k]=false;
				$regexppath=str_replace('\:'.$name,'([^\/]+)',$regexppath);
			}
		}
		return $regexppath;
	}
	protected function appendUrlInfo($u,$path,$secondaryAction){
		$cuisel=$u->getFullSel();
		$arr=array(1,$u->entryPointUrl,$u->isHttps,$u->params,$u->escapes,$path,$secondaryAction,$u->statics);
		if(isset($this->createUrlInfos[$cuisel])){
			if($this->createUrlInfos[$cuisel][0]==4){
				$this->createUrlInfos[$cuisel][]=$arr;
			}
			else{
				$this->createUrlInfos[$cuisel]=array(4,$this->createUrlInfos[$cuisel],$arr);
			}
		}
		else{
			$this->createUrlInfos[$cuisel]=$arr;
		}
	}
	protected function readInclude($url,$uInfo){
		$file=(string)$url['include'];
		$pathinfo='/'.trim((string)$url['pathinfo'],'/');
		if(!isset($this->modulesPath[$uInfo->module]))
			throw new Exception('urls.xml: the module '.$uInfo->module.' does not exist');
		$path=$this->modulesPath[$uInfo->module];
		if(!file_exists($path.$file))
			throw new Exception('urls.xml: include file '.$file.' of the module '.$uInfo->module.' does not exist');
		$xml=simplexml_load_file($path.$file);
		if(!$xml){
			throw new Exception('urls.xml: include file '.$file.' of the module '.$uInfo->module.' is not a valid xml file');
		}
		$optionalTrailingSlash=(isset($xml['optionalTrailingSlash'])&&$xml['optionalTrailingSlash']=='true');
		foreach($xml->url as $url){
			$u=clone $uInfo;
			$u->action=(string)$url['action'];
			if(strpos($u->action,':')===false){
				$u->action='default:'.$u->action;
			}
			if(isset($url['actionoverride'])){
				$u->actionOverride=preg_split("/[\s,]+/",(string)$url['actionoverride']);
				foreach($u->actionOverride as &$each){
					if(strpos($each,':')===false){
						$each='default:'.$each;
					}
				}
			}
			if(isset($url['handler'])){
				$this->newHandler($u,$url,$pathinfo);
				continue;
			}
			if(isset($url['pathinfo'])){
				$path=$pathinfo.($pathinfo!='/'?'/':'').trim((string)$url['pathinfo'],'/');
				$regexppath=$this->extractDynamicParams($url,$path,$u);
			}
			else{
				$regexppath='.*';
				$path='';
			}
			$tempOptionalTrailingSlash=$optionalTrailingSlash;
			if(isset($url['optionalTrailingSlash'])){
				$tempOptionalTrailingSlash=($url['optionalTrailingSlash']=='true');
			}
			if($tempOptionalTrailingSlash){
				if(substr($regexppath,-1)=='/'){
					$regexppath.='?';
				}
				else{
					$regexppath.='\/?';
				}
			}
			foreach($url->static as $var){
				$u->statics[(string)$var['name']]=(string)$var['value'];
			}
			$this->parseInfos[]=array($u->module,$u->action,'!^'.$regexppath.'$!',$u->params,$u->escapes,$u->statics,$u->actionOverride);
			$this->appendUrlInfo($u,$path,false);
			if($u->actionOverride){
				foreach($u->actionOverride as $ao){
					$u->action=$ao;
					$this->appendUrlInfo($u,$path,true);
				}
			}
		}
	}
}
