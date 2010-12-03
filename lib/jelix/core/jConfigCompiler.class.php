<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   core
* @author       Laurent Jouanneau
* @contributor  Thibault Piront (nuKs), Christophe Thiriot, Philippe Schelté
* @copyright    2006-2009 Laurent Jouanneau
* @copyright    2007 Thibault Piront, 2008 Christophe Thiriot, 2008 Philippe Schelté
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jConfigCompiler{
	static protected $commonConfig;
	private function __construct(){}
	static public function read($configFile,$allModuleInfo=false,$isCli=false,$pseudoScriptName=''){
		if(JELIX_APP_TEMP_PATH=='/'){
			throw new Exception('Application temp directory doesn\'t exist !',3);
		}
		if(!is_writable(JELIX_APP_TEMP_PATH)){
			throw new Exception('Application temp directory is not writable',4);
		}
		self::$commonConfig=jIniFile::read(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php',true);
		$config=jIniFile::read(JELIX_LIB_CORE_PATH.'defaultconfig.ini.php');
		if(self::$commonConfig){
			self::_mergeConfig($config,self::$commonConfig);
		}
		if($configFile!='defaultconfig.ini.php'){
			if(!file_exists(JELIX_APP_CONFIG_PATH.$configFile))
				throw new Exception("Config file $configFile is missing !",5);
			if(false===($userConfig=parse_ini_file(JELIX_APP_CONFIG_PATH.$configFile,true)))
				throw new Exception("Syntax error in the config file $configFile !",6);
			self::_mergeConfig($config,$userConfig);
		}
		$config=(object) $config;
		self::prepareConfig($config,$allModuleInfo,$isCli,$pseudoScriptName);
		self::$commonConfig=null;
		return $config;
	}
	static public function readAndCache($configFile,$isCli=null,$pseudoScriptName=''){
		if($isCli===null)
			$isCli=(PHP_SAPI=='cli');
		$config=self::read($configFile,false,$isCli,$pseudoScriptName);
		if(BYTECODE_CACHE_EXISTS){
			$filename=JELIX_APP_TEMP_PATH.str_replace('/','~',$configFile).'.conf.php';
			if($f=@fopen($filename,'wb')){
				fwrite($f,'<?php $config = '.var_export(get_object_vars($config),true).";\n?>");
				fclose($f);
			}else{
				throw new Exception('(24)Error while writing config cache file '.$filename);
			}
		}else{
			jIniFile::write(get_object_vars($config),JELIX_APP_TEMP_PATH.str_replace('/','~',$configFile).'.resultini.php',";<?php die('');?>\n");
		}
		return $config;
	}
	static protected function prepareConfig($config,$allModuleInfo,$isCli,$pseudoScriptName){
		$config->isWindows=(DIRECTORY_SEPARATOR==='\\');
		if(trim($config->startAction)==''){
			$config->startAction=':';
		}
		if($config->domainName==""&&isset($_SERVER['SERVER_NAME']))
			$config->domainName=$_SERVER['SERVER_NAME'];
		$config->_allBasePath=array();
		if($pseudoScriptName){
			$config->urlengine['urlScript']=$pseudoScriptName;
		}
		else{
			if($config->urlengine['scriptNameServerVariable']==''){
				$config->urlengine['scriptNameServerVariable']=self::_findServerName($config->urlengine['entrypointExtension'],$isCli);
			}
			$config->urlengine['urlScript']=$_SERVER[$config->urlengine['scriptNameServerVariable']];
		}
		$lastslash=strrpos($config->urlengine['urlScript'],'/');
		if($isCli){
			if($lastslash===false){
				$config->urlengine['urlScriptPath']=($pseudoScriptName? JELIX_APP_PATH.'/scripts/': getcwd().'/');
				$config->urlengine['urlScriptName']=$config->urlengine['urlScript'];
			}
			else{
				$config->urlengine['urlScriptPath']=getcwd().'/'.substr($config->urlengine['urlScript'],0,$lastslash).'/';
				$config->urlengine['urlScriptName']=substr($config->urlengine['urlScript'],$lastslash+1);
			}
			$basepath=$config->urlengine['urlScriptPath'];
			$snp=$config->urlengine['urlScriptName'];
			$config->urlengine['urlScript']=$basepath.$snp;
		}
		else{
			$config->urlengine['urlScriptPath']=substr($config->urlengine['urlScript'],0,$lastslash).'/';
			$config->urlengine['urlScriptName']=substr($config->urlengine['urlScript'],$lastslash+1);
			$basepath=$config->urlengine['basePath'];
			if($basepath==''){
				$basepath=$config->urlengine['urlScriptPath'];
			}
			elseif($basepath!='/'){
				if($basepath[0]!='/')$basepath='/'.$basepath;
				if(substr($basepath,-1)!='/')$basepath.='/';
				if($pseudoScriptName){
					$config->urlengine['urlScriptPath']=substr($basepath,0,-1).$config->urlengine['urlScriptPath'];
					$config->urlengine['urlScript']=$config->urlengine['urlScriptPath'].$config->urlengine['urlScriptName'];
				}
				elseif(strpos($config->urlengine['urlScriptPath'],$basepath)!==0){
					throw new Exception('Jelix Error: basePath ('.$basepath.') in config file doesn\'t correspond to current base path. You should setup it to '.$config->urlengine['urlScriptPath']);
				}
			}
			$config->urlengine['basePath']=$basepath;
			if($config->urlengine['jelixWWWPath'][0]!='/')
				$config->urlengine['jelixWWWPath']=$basepath.$config->urlengine['jelixWWWPath'];
			if($config->urlengine['jqueryPath'][0]!='/')
				$config->urlengine['jqueryPath']=$basepath.$config->urlengine['jqueryPath'];
			$snp=substr($config->urlengine['urlScript'],strlen($basepath));
		}
		$pos=strrpos($snp,$config->urlengine['entrypointExtension']);
		if($pos!==false){
			$snp=substr($snp,0,$pos);
		}
		$config->urlengine['urlScriptId']=$snp;
		$config->urlengine['urlScriptIdenc']=rawurlencode($snp);
		self::_loadModuleInfo($config,$allModuleInfo);
		self::_loadPluginsPathList($config);
		$coordplugins=array();
		foreach($config->coordplugins as $name=>$conf){
			if(!isset($config->_pluginsPathList_coord[$name])){
				throw new Exception("Error in the main configuration. The coord plugin $name doesn't exist!",7);
			}
			if($conf){
				if($conf!='1'&&!file_exists(JELIX_APP_CONFIG_PATH.$conf)){
					throw new Exception("Error in the main configuration. Configuration file '$conf' for coord plugin $name doesn't exist!",8);
				}
				$coordplugins[$name]=$conf;
			}
		}
		$config->coordplugins=$coordplugins;
		self::_initResponsesPath($config->responses);
		self::_initResponsesPath($config->_coreResponses);
		if(trim($config->timeZone)===''){
			$tz=ini_get('date.timezone');
			if($tz!='')
				$config->timeZone=$tz;
			else
				$config->timeZone="Europe/Paris";
		}
		if($config->sessions['storage']=='files'){
			$config->sessions['files_path']=str_replace(array('lib:','app:'),array(LIB_PATH,JELIX_APP_PATH),$config->sessions['files_path']);
		}
		$config->sessions['_class_to_load']=array();
		if($config->sessions['loadClasses']!=''){
			$list=preg_split('/ *, */',$config->sessions['loadClasses']);
			foreach($list as $sel){
				if(preg_match("/^([a-zA-Z0-9_\.]+)~([a-zA-Z0-9_\.\\/]+)$/",$sel,$m)){
					if(!isset($config->_modulesPathList[$m[1]])){
						throw new Exception('Error in config files, loadClasses: '.$m[1].' is not a valid or activated module');
					}
					if(($p=strrpos($m[2],'/'))!==false){
						$className=substr($m[2],$p+1);
						$subpath=substr($m[2],0,$p+1);
					}else{
						$className=$m[2];
						$subpath='';
					}
					$path=$config->_modulesPathList[$m[1]].'classes/'.$subpath.$className.'.class.php';
					if(!file_exists($path)||strpos($subpath,'..')!==false){
						throw new Exception('Error in config files, loadClasses, bad class selector: '.$sel);
					}
					$config->sessions['_class_to_load'][]=$path;
				}
				else
					throw new Exception('Error in config files, loadClasses, bad class selector: '.$sel);
			}
		}
	}
	static protected function _loadModuleInfo($config,$allModuleInfo){
		if($config->disableInstallers){
			$installation=array();
		}
		else if(!file_exists(JELIX_APP_CONFIG_PATH.'installer.ini.php')){
			if($allModuleInfo)
				$installation=array();
			else
				throw new Exception("installer.ini.php doesn't exist! You must install your application.\n",9);
		}
		else
			$installation=parse_ini_file(JELIX_APP_CONFIG_PATH.'installer.ini.php',true);
		$section=$config->urlengine['urlScriptId'];
		if(!isset($installation[$section]))
			$installation[$section]=array();
		$list=preg_split('/ *, */',$config->modulesPath);
		$list=array_merge($list,preg_split('/ *, */',self::$commonConfig['modulesPath']));
		array_unshift($list,JELIX_LIB_PATH.'core-modules/');
		$pathChecked=array();
		foreach($list as $k=>$path){
			if(trim($path)=='')continue;
			$p=str_replace(array('lib:','app:'),array(LIB_PATH,JELIX_APP_PATH),$path);
			if(!file_exists($p)){
				throw new Exception('The path, '.$path.' given in the jelix config, doesn\'t exist !',10);
			}
			if(substr($p,-1)!='/')
				$p.='/';
			if(in_array($p,$pathChecked))
				continue;
			$pathChecked[]=$p;
			if($k!=0&&$config->compilation['checkCacheFiletime'])
				$config->_allBasePath[]=$p;
			if($handle=opendir($p)){
				while(false!==($f=readdir($handle))){
					if($f[0]!='.'&&is_dir($p.$f)){
						if($config->disableInstallers)
							$installation[$section][$f.'.installed']=1;
						else if(!isset($installation[$section][$f.'.installed']))
							$installation[$section][$f.'.installed']=0;
						if($f=='jelix'){
							$config->modules['jelix.access']=2;
						}
						else{
							if($config->enableAllModules){
								if($config->disableInstallers
									||$installation[$section][$f.'.installed']
									||$allModuleInfo)
									$config->modules[$f.'.access']=2;
								else
									$config->modules[$f.'.access']=0;
							}
							else if(!isset($config->modules[$f.'.access'])){
								$config->modules[$f.'.access']=0;
							}
							else if($config->modules[$f.'.access']==0){
								if(isset(self::$commonConfig['modules'][$f.'.access'])
									&&self::$commonConfig['modules'][$f.'.access'] > 0)
									$config->modules[$f.'.access']=3;
							}
							else if(!$installation[$section][$f.'.installed']){
								if(!$allModuleInfo)
									$config->modules[$f.'.access']=0;
							}
						}
						if(!isset($installation[$section][$f.'.dbprofile']))
							$config->modules[$f.'.dbprofile']='default';
						else
							$config->modules[$f.'.dbprofile']=$installation[$section][$f.'.dbprofile'];
						if($allModuleInfo){
							if(!isset($installation[$section][$f.'.version']))
								$installation[$section][$f.'.version']='';
							if(!isset($installation[$section][$f.'.dataversion']))
								$installation[$section][$f.'.dataversion']='';
							if(!isset($installation['__modules_data'][$f.'.contexts']))
								$installation['__modules_data'][$f.'.contexts']='';
							$config->modules[$f.'.version']=$installation[$section][$f.'.version'];
							$config->modules[$f.'.dataversion']=$installation[$section][$f.'.dataversion'];
							$config->modules[$f.'.installed']=$installation[$section][$f.'.installed'];
							$config->_allModulesPathList[$f]=$p.$f.'/';
						}
						if($config->modules[$f.'.access']==3){
							$config->_externalModulesPathList[$f]=$p.$f.'/';
						}
						elseif($config->modules[$f.'.access'])
							$config->_modulesPathList[$f]=$p.$f.'/';
					}
				}
				closedir($handle);
			}
		}
	}
	static protected function _loadPluginsPathList($config){
		$list=preg_split('/ *, */',$config->pluginsPath);
		array_unshift($list,JELIX_LIB_PATH.'plugins/');
		foreach($list as $k=>$path){
			if(trim($path)=='')continue;
			$p=str_replace(array('lib:','app:'),array(LIB_PATH,JELIX_APP_PATH),$path);
			if(!file_exists($p)){
				trigger_error('The path, '.$path.' given in the jelix config, doesn\'t exists !',E_USER_ERROR);
				exit;
			}
			if(substr($p,-1)!='/')
				$p.='/';
			if($handle=opendir($p)){
				while(false!==($f=readdir($handle))){
					if($f[0]!='.'&&is_dir($p.$f)){
						if($subdir=opendir($p.$f)){
							if($k!=0&&$config->compilation['checkCacheFiletime'])
								$config->_allBasePath[]=$p.$f.'/';
							while(false!==($subf=readdir($subdir))){
								if($subf[0]!='.'&&is_dir($p.$f.'/'.$subf)){
									if($f=='tpl'){
										$prop='_tplpluginsPathList_'.$subf;
										$config->{$prop}[]=$p.$f.'/'.$subf.'/';
									}else{
										$prop='_pluginsPathList_'.$f;
										$config->{$prop}[$subf]=$p.$f.'/'.$subf.'/';
									}
								}
							}
							closedir($subdir);
						}
					}
				}
				closedir($handle);
			}
		}
	}
	static private function _findServerName($ext,$isCli){
		$varname='';
		$extlen=strlen($ext);
		if(strrpos($_SERVER['SCRIPT_NAME'],$ext)===(strlen($_SERVER['SCRIPT_NAME'])- $extlen)
			||$isCli){
			return 'SCRIPT_NAME';
		}else if(isset($_SERVER['REDIRECT_URL'])
				&&strrpos($_SERVER['REDIRECT_URL'],$ext)===(strlen($_SERVER['REDIRECT_URL'])-$extlen)){
			return 'REDIRECT_URL';
		}else if(isset($_SERVER['ORIG_SCRIPT_NAME'])
				&&strrpos($_SERVER['ORIG_SCRIPT_NAME'],$ext)===(strlen($_SERVER['ORIG_SCRIPT_NAME'])- $extlen)){
			return 'ORIG_SCRIPT_NAME';
		}
		throw new Exception('In config file the parameter urlengine:scriptNameServerVariable is empty and Jelix doesn\'t find
            the variable in $_SERVER which contains the script name. You must see phpinfo and setup this parameter in your config file.',11);
	}
	static private function _initResponsesPath(&$list){
		$copylist=$list;
		foreach($copylist as $type=>$class){
			if(file_exists($path=JELIX_LIB_CORE_PATH.'response/'.$class.'.class.php')){
				$list[$type.'.path']=$path;
			}elseif(file_exists($path=JELIX_APP_PATH.'responses/'.$class.'.class.php')){
				$list[$type.'.path']=$path;
			}else{
				throw new Exception('Configuration Error: the class file of the response type "'.$type.'" is not found ('.$path.')',12);
			}
		}
	}
	static private function _mergeConfig(&$array,$tomerge){
		foreach($tomerge as $k=>$v){
			if(!isset($array[$k])){
				$array[$k]=$v;
				continue;
			}
			if($k[1]=='_')
				continue;
			if(is_array($v)){
				$array[$k]=array_merge($array[$k],$v);
			}else{
				$array[$k]=$v;
			}
		}
	}
}
