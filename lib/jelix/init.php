<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* Initialize all defines and includes necessary files
*
* Some lines of code were get from Copix project (Copix 2.3dev20050901)
* and are copyrighted 2001-2005 CopixTeam (LGPL Licence)
* @package  jelix
* @subpackage core
* @author   Laurent Jouanneau
* @author Croes Gerald
* @contributor Loic Mathaud, Julien Issler
* @copyright 2005-2014 Laurent Jouanneau
* @copyright 2001-2005 CopixTeam
* @copyright 2006 Loic Mathaud
* @copyright 2007-2009 Julien Issler
* @link http://www.copix.org
* @link     http://www.jelix.org
* @licence  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
define('JELIX_VERSION','1.6.17');
define('JELIX_NAMESPACE_BASE','http://jelix.org/ns/');
define('JELIX_LIB_PATH',__DIR__.'/');
define('JELIX_LIB_CORE_PATH',JELIX_LIB_PATH.'core/');
define('JELIX_LIB_UTILS_PATH',JELIX_LIB_PATH.'utils/');
define('LIB_PATH',dirname(JELIX_LIB_PATH).'/');
define('BYTECODE_CACHE_EXISTS',function_exists('opcache_compile_file')||function_exists('apc_cache_info')||function_exists('eaccelerator_info')||function_exists('xcache_info'));
error_reporting(E_ALL | E_STRICT);
class jApp{
	protected static $tempBasePath='';
	protected static $appPath='';
	protected static $varPath='';
	protected static $logPath='';
	protected static $configPath='';
	protected static $wwwPath='';
	protected static $scriptPath='';
	protected static $_isInit=false;
	protected static $env='www/';
	protected static $configAutoloader=null;
	public static function initPaths($appPath,
								$wwwPath=null,
								$varPath=null,
								$logPath=null,
								$configPath=null,
								$scriptPath=null
								){
		self::$appPath=$appPath;
		self::$wwwPath=(is_null($wwwPath)?$appPath.'www/':$wwwPath);
		self::$varPath=(is_null($varPath)?$appPath.'var/':$varPath);
		self::$logPath=(is_null($logPath)?self::$varPath.'log/':$logPath);
		self::$configPath=(is_null($configPath)?self::$varPath.'config/':$configPath);
		self::$scriptPath=(is_null($scriptPath)?$appPath.'scripts/':$scriptPath);
		self::$_isInit=true;
		self::$_coord=null;
		self::$_config=null;
		self::$configAutoloader=null;
		self::$_mainConfigFile=null;
	}
	public static function isInit(){return self::$_isInit;}
	public static function appPath($file=''){return self::$appPath.$file;}
	public static function varPath($file=''){return self::$varPath.$file;}
	public static function logPath($file=''){return self::$logPath.$file;}
	public static function configPath($file=''){return self::$configPath.$file;}
	public static function wwwPath($file=''){return self::$wwwPath.$file;}
	public static function scriptsPath($file=''){return self::$scriptPath.$file;}
	public static function tempPath($file=''){return self::$tempBasePath.self::$env.$file;}
	public static function tempBasePath(){return self::$tempBasePath;}
	public static function setTempBasePath($path){
		self::$tempBasePath=$path;
	}
	public static function setEnv($env){
		if(substr($env,-1)!='/')
			$env.='/';
		self::$env=$env;
	}
	public static function urlBasePath(){
		if(!self::$_config||!isset(self::$_config->urlengine['basePath']))
			return null;
		return self::$_config->urlengine['basePath'];
	}
	protected static $_config=null;
	public static function config(){
		return self::$_config;
	}
	public static function setConfig($config){
		if(self::$configAutoloader){
			spl_autoload_unregister(array(self::$configAutoloader,'loadClass'));
			self::$configAutoloader=null;
		}
		self::$_config=$config;
		if($config){
			date_default_timezone_set(self::$_config->timeZone);
			self::$configAutoloader=new jConfigAutoloader($config);
			spl_autoload_register(array(self::$configAutoloader,'loadClass'));
			foreach(self::$_config->_autoload_autoloader as $autoloader)
				require_once($autoloader);
		}
	}
	public static function loadConfig($configFile,$enableErrorHandler=true){
		if($enableErrorHandler){
			jBasicErrorHandler::register();
		}
		if(is_object($configFile))
			self::setConfig($configFile);
		else
			self::setConfig(jConfig::load($configFile));
		self::$_config->enableErrorHandler=$enableErrorHandler;
	}
	protected static $_mainConfigFile=null;
	public static function mainConfigFile(){
		if(self::$_mainConfigFile)
			return self::$_mainConfigFile;
		$configFileName=self::configPath('mainconfig.ini.php');
		if(!file_exists($configFileName)){
			$configFileName=self::configPath('defaultconfig.ini.php');
			trigger_error("the config file defaultconfig.ini.php is deprecated and will be removed in the next major release",E_USER_DEPRECATED);
		}
		self::$_mainConfigFile=$configFileName;
		return $configFileName;
	}
	protected static $_coord=null;
	public static function coord(){
		return self::$_coord;
	}
	public static function setCoord($coord){
		self::$_coord=$coord;
	}
	protected static $contextBackup=array();
	public static function saveContext(){
		if(self::$_config)
			$conf=clone self::$_config;
		else
			$conf=null;
		if(self::$_coord)
			$coord=clone self::$_coord;
		else
			$coord=null;
		self::$contextBackup[]=array(self::$appPath,self::$varPath,self::$logPath,
										self::$configPath,self::$wwwPath,self::$scriptPath,
										self::$tempBasePath,self::$env,$conf,$coord,
										self::$modulesContext,self::$configAutoloader,
										self::$_mainConfigFile);
	}
	public static function restoreContext(){
		if(!count(self::$contextBackup))
			return;
		list(self::$appPath,self::$varPath,self::$logPath,self::$configPath,
			self::$wwwPath,self::$scriptPath,self::$tempBasePath,self::$env,
			$conf,self::$_coord,self::$modulesContext,self::$configAutoloader,
			self::$_mainConfigFile)=array_pop(self::$contextBackup);
		self::setConfig($conf);
	}
	public static function loadPlugin($name,$type,$suffix,$classname,$args=null){
		if(!class_exists($classname,false)){
			$optname='_pluginsPathList_'.$type;
			if(!isset(jApp::config()->$optname))
				return null;
			$opt=& jApp::config()->$optname;
			require_once($opt[$name].$name.$suffix);
		}
		if(!is_null($args))
			return new $classname($args);
		else
			return new $classname();
	}
	public static function isModuleEnabled($moduleName,$includingExternal=false){
		if(!self::$_config)
			throw new Exception('Configuration is not loaded');
		if($includingExternal&&isset(self::$_config->_externalModulesPathList[$moduleName])){
			return true;
		}
		return isset(self::$_config->_modulesPathList[$moduleName]);
	}
	public static function getModulePath($module,$includingExternal=false){
		if(!self::$_config)
			throw new Exception('Configuration is not loaded');
		if(!isset(self::$_config->_modulesPathList[$module])){
			if($includingExternal&&isset(self::$_config->_externalModulesPathList[$module])){
				return self::$_config->_externalModulesPathList[$module];
			}
			throw new Exception('getModulePath : invalid module name');
		}
		return self::$_config->_modulesPathList[$module];
	}
	static protected $modulesContext=array();
	static function pushCurrentModule($module){
		array_push(self::$modulesContext,$module);
	}
	static function popCurrentModule(){
		return array_pop(self::$modulesContext);
	}
	static function getCurrentModule(){
		return end(self::$modulesContext);
	}
}
if(function_exists('jelix_version')){
	return;
}
else{
function jelix_read_ini($fileName,$config=null){
	$conf=jIniFile::read($fileName);
	if($config!==null){
		foreach($conf as $k=>$v){
			if(!isset($config->$k)){
				$config->$k=$v;
				continue;
			}
			if($k[1]=='_')
				continue;
			if(is_array($v)){
				$config->$k=array_merge($config->$k,$v);
			}
			else{
				$config->$k=$v;
			}
		}
		return $config;
	}
	$conf=(object) $conf;
	return $conf;
}
function jelix_scan_module_sel($selStr,$selObj){
	if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_\.]+)$/",$selStr,$m)){
		if($m[1]!=''&&$m[2]!=''){
			$selObj->module=$m[2];
		}else{
			$selObj->module='';
		}
		$selObj->resource=$m[3];
		return true;
	}
	return false;
}
function jelix_scan_action_sel($selStr,$selObj,$actionName){
	if(preg_match("/^(?:([a-zA-Z0-9_\.]+|\#)~)?([a-zA-Z0-9_:]+|\#)?(?:@([a-zA-Z0-9_]+))?$/",$selStr,$m)){
		$m=array_pad($m,4,'');
		$selObj->module=$m[1];
		if($m[2]=='#')
			$selObj->resource=$actionName;
		else
			$selObj->resource=$m[2];
		$r=explode(':',$selObj->resource);
		if(count($r)==1){
			$selObj->controller='default';
			$selObj->method=$r[0]==''?'index':$r[0];
		}else{
			$selObj->controller=$r[0]=='' ? 'default':$r[0];
			$selObj->method=$r[1]==''?'index':$r[1];
		}
		$selObj->resource=$selObj->controller.':'.$selObj->method;
		$selObj->request=$m[3];
		return true;
	}
	return false;
}
function jelix_scan_class_sel($selStr,$selObj){
	if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_\.\\/]+)$/",$selStr,$m)){
		$selObj->module=$m[2];
		$selObj->resource=$m[3];
		if(($p=strrpos($m[3],'/'))!==false){
			$selObj->className=substr($m[3],$p+1);
			$selObj->subpath=substr($m[3],0,$p+1);
		}
		else{
			$selObj->className=$m[3];
			$selObj->subpath='';
		}
		return true;
	}
	return false;
}
function jelix_scan_locale_sel($selStr,$selObj){
	if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_]+)\.([a-zA-Z0-9_\-\.]+)$/",$selStr,$m)){
		if($m[1]!=''&&$m[2]!=''){
			$selObj->module=$m[2];
		}
		else{
			$selObj->module='';
		}
		$selObj->resource=$m[3];
		$selObj->fileKey=$m[3];
		$selObj->messageKey=$m[4];
		return true;
	}
	return false;
}
}
interface jICoordPlugin{
	public function __construct($config);
	public function beforeAction($params);
	public function beforeOutput();
	public function afterProcess();
}
interface jISelector{
	public function getPath();
	public function getCompiledFilePath();
	public function getCompiler();
	public function useMultiSourceCompiler();
	public function toString($full=false);
}
interface jIUrlEngine{
	public function parse($scriptNamePath,$pathinfo,$params);
	public function parseFromRequest($request,$params);
	public function create($urlact);
}
class jBasicErrorHandler{
	static $errorCode=array(
		E_ERROR=>'error',
		E_RECOVERABLE_ERROR=>'error',
		E_WARNING=>'warning',
		E_NOTICE=>'notice',
		E_DEPRECATED=>'deprecated',
		E_USER_ERROR=>'error',
		E_USER_WARNING=>'warning',
		E_USER_NOTICE=>'notice',
		E_USER_DEPRECATED=>'deprecated',
		E_STRICT=>'strict'
	);
	static function register(){
		set_error_handler(array('jBasicErrorHandler','errorHandler'));
		set_exception_handler(array('jBasicErrorHandler','exceptionHandler'));
	}
	static function errorHandler($errno,$errmsg,$filename,$linenum,$errcontext){
		if(error_reporting()==0)
			return;
		if(preg_match('/^\s*\((\d+)\)(.+)$/',$errmsg,$m)){
			$code=$m[1];
			$errmsg=$m[2];
		}
		else{
			$code=1;
		}
		if(!isset(self::$errorCode[$errno])){
			$errno=E_ERROR;
		}
		$codestr=self::$errorCode[$errno];
		$trace=debug_backtrace();
		array_shift($trace);
		self::handleError($codestr,$errno,$errmsg,$filename,$linenum,$trace);
	}
	static function exceptionHandler($e){
		self::handleError('error',$e->getCode(),$e->getMessage(),$e->getFile(),
						$e->getLine(),$e->getTrace());
	}
	static public $initErrorMessages=array();
	static function handleError($type,$code,$message,$file,$line,$trace){
		$errorLog=new jLogErrorMessage($type,$code,$message,$file,$line,$trace);
		if($type!='error'){
			self::$initErrorMessages[]=$errorLog;
			return;
		}
		else if(jServer::isCLI()){
			while(ob_get_level()&&@ob_end_clean());
			echo 'Error during initialization: \n';
			foreach(self::$initErrorMessages as $err){
				@error_log($err->getFormatedMessage()."\n",3,jApp::logPath('errors.log'));
				echo '* '.$err->getMessage().' ('.$err->getFile().' '.$err->getLine().")\n";
			}
			@error_log($errorLog->getFormatedMessage()."\n",3,jApp::logPath('errors.log'));
			echo '* '.$message.' ('.$file.' '.$line.")\n";
		}
		else{
			while(ob_get_level()&&@ob_end_clean());
			foreach(self::$initErrorMessages as $err){
				@error_log($err->getFormatedMessage()."\n",3,jApp::logPath('errors.log'));
			}
			@error_log($errorLog->getFormatedMessage()."\n",3,jApp::logPath('errors.log'));
			$msg=$errorLog->getMessage();
			if(strpos($msg,'--')!==false){
				list($msg,$bin)=explode('--',$msg,2);
			}
			if(isset($_SERVER['HTTP_ACCEPT'])&&strstr($_SERVER['HTTP_ACCEPT'],'text/html')){
				if(file_exists(jApp::appPath('responses/error.en_US.php')))
					$file=jApp::appPath('responses/error.en_US.php');
				else
					$file=JELIX_LIB_CORE_PATH.'response/error.en_US.php';
				$HEADTOP='';
				$HEADBOTTOM='';
				$BODYTOP='';
				$BODYBOTTOM=htmlspecialchars($msg);
				$BASEPATH=jApp::urlBasePath();
				if($BASEPATH==''){
					$BASEPATH='/';
				}
				header("HTTP/1.1 500 Internal jelix error");
				header('Content-type: text/html');
				include($file);
			}
			else{
				header("HTTP/1.1 500 Internal jelix error");
				header('Content-type: text/plain');
				echo 'Error during initialization. '.$msg;
			}
		}
		exit(1);
	}
}
class jException extends Exception{
	protected $localeKey='';
	protected $localeParams=array();
	public function __construct($localekey,$localeParams=array(),$code=1,$lang=null,$charset=null){
		$this->localeKey=$localekey;
		$this->localeParams=$localeParams;
		try{
			$message=jLocale::get($localekey,$localeParams,$lang,$charset);
		}catch(Exception $e){
			$message=$e->getMessage();
		}
		if(preg_match('/^\s*\((\d+)\)(.+)$/ms',$message,$m)){
			$code=$m[1];
			$message=$m[2];
		}
		parent::__construct($message,$code);
	}
	public function getLocaleParameters(){
		return $this->localeParams;
	}
	public function getLocaleKey(){
		return $this->localeKey;
	}
}
class jConfig{
	public static $fromCache=true;
	private function __construct(){}
	static public function load($configFile){
		$config=array();
		$file=jApp::tempPath().str_replace('/','~',$configFile);
		if(BYTECODE_CACHE_EXISTS)
			$file.='.conf.php';
		else
			$file.='.resultini.php';
		self::$fromCache=true;
		if(!file_exists($file)){
			self::$fromCache=false;
		}
		else{
			$t=filemtime($file);
			$dc=jApp::mainConfigFile();
			$lc=jApp::configPath('localconfig.ini.php');
			if((file_exists($dc)&&filemtime($dc)>$t)
				||filemtime(jApp::configPath($configFile))>$t
				||(file_exists($lc)&&filemtime($lc)>$t)){
				self::$fromCache=false;
			}
			else{
				if(BYTECODE_CACHE_EXISTS){
					include($file);
					$config=(object) $config;
				}else{
					$config=jelix_read_ini($file);
				}
				if($config->compilation['checkCacheFiletime']){
					foreach($config->_allBasePath as $path){
						if(!file_exists($path)||filemtime($path)>$t){
							self::$fromCache=false;
							break;
						}
					}
				}
			}
		}
		if(!self::$fromCache){
			return jConfigCompiler::readAndCache($configFile);
		}
		else{
			return $config;
		}
	}
}
class jExceptionSelector extends jException{}
class jSelectorFactory{
	private function __construct(){}
	static public function create($selstr,$defaulttype=false){
		if(is_string($defaulttype)&&strpos($selstr,':')===false){
			$selstr="$defaulttype:$selstr";
		}
		if(preg_match("/^([a-z]{3,5})\:([\w~\/\.]+)$/",$selstr,$m)){
			$cname='jSelector'.$m[1];
			if(class_exists($cname)){
				$sel=new $cname($m[2]);
				return $sel;
			}
		}
		throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($selstr,''));
	}
}
class jServer{
	static function isCLI(){
		if(PHP_SAPI!='cli'&&strpos(PHP_SAPI,'cgi')===false){
			return false;
		}
		if(PHP_SAPI!='cli'){
			if(isset($_SERVER['HTTP_HOST'])||isset($_SERVER['REDIRECT_URL'])||isset($_SERVER['SERVER_PORT'])){
				return false;
			}
			header('Content-type: text/plain');
			if(!isset($_SERVER['argv'])){
				$_SERVER['argv']=array_keys($_GET);
				$_SERVER['argc']=count($_GET);
			}
			if(!isset($_SERVER['SCRIPT_NAME'])){
				$_SERVER['SCRIPT_NAME']=$_SERVER['argv'][0];
			}
			if(!isset($_SERVER['DOCUMENT_ROOT'])){
				$_SERVER['DOCUMENT_ROOT']='';
			}
		}
		return true;
	}
}
abstract class jSelectorModule implements jISelector{
	public $module=null;
	public $resource=null;
	protected $type='_module';
	protected $_dirname='';
	protected $_suffix='';
	protected $_cacheSuffix='.php';
	protected $_path;
	protected $_cachePath;
	protected $_compiler=null;
	protected $_compilerPath;
	protected $_useMultiSourceCompiler=false;
	function __construct($sel){
		if(jelix_scan_module_sel($sel,$this)){
			if($this->module==''){
				$this->module=jApp::getCurrentModule();
			}
			$this->_createPath();
			$this->_createCachePath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	public function getPath(){
		return $this->_path;
	}
	public function getCompiledFilePath(){
		return $this->_cachePath;
	}
	public function getCompiler(){
		if($this->_compiler==null)return null;
		$n=$this->_compiler;
		require_once($this->_compilerPath);
		$o=new $n();
		return $o;
	}
	public function useMultiSourceCompiler(){
		return $this->_useMultiSourceCompiler;
	}
	public function toString($full=false){
		if($full)
			return $this->type.':'.$this->module.'~'.$this->resource;
		else
			return $this->module.'~'.$this->resource;
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString(true));
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
		if(!is_readable($this->_path)){
			if($this->type=='loc'){
				throw new Exception('(202) The file of the locale key "'.$this->toString().'" (charset '.$this->charset.', lang '.$this->locale.') does not exist');
			}elseif($this->toString()=='jelix~errors.selector.invalid.target'){
				throw new Exception("Jelix Panic ! don't find localization files to show you an other error message !");
			}else{
				throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),$this->type));
			}
		}
	}
	protected function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/'.$this->_dirname.$this->module.'~'.$this->resource.$this->_cacheSuffix);
	}
}
class jSelectorActFast extends jSelectorModule{
	protected $type='act';
	public $request='';
	public $controller='';
	public $method='';
	protected $_dirname='actions/';
	function __construct($requestType,$module,$action){
		$this->module=$module;
		$r=explode(':',$action);
		if(count($r)==1){
			$this->controller='default';
			$this->method=$r[0]==''?'index':$r[0];
		}else{
			$this->controller=$r[0]=='' ? 'default':$r[0];
			$this->method=$r[1]==''?'index':$r[1];
		}
		if(substr($this->method,0,2)=='__')
			throw new jExceptionSelector('jelix~errors.selector.method.invalid',$this->toString());
		$this->resource=$this->controller.':'.$this->method;
		$this->request=$requestType;
		$this->_createPath();
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}else{
			$this->_path=jApp::config()->_modulesPathList[$this->module].'controllers/'.$this->controller.'.'.$this->request.'.php';
		}
	}
	protected function _createCachePath(){
		$this->_cachePath='';
	}
	public function toString($full=false){
		if($full)
			return $this->type.':'.$this->module.'~'.$this->resource.'@'.$this->request;
		else
			return $this->module.'~'.$this->resource.'@'.$this->request;
	}
	public function getClass(){
		return $this->controller.'Ctrl';
	}
	public function isEqualTo(jSelectorActFast $otherAction){
		return($this->module==$otherAction->module&&
				$this->controller==$otherAction->controller&&
				$this->method==$otherAction->method&&
				$this->request==$otherAction->request
				);
	}
}
class jSelectorAct extends jSelectorActFast{
	protected $forUrl=false;
	function __construct($sel,$enableRequestPart=false,$toRetrieveUrl=false){
		$coord=jApp::coord();
		$this->forUrl=$toRetrieveUrl;
		if($coord->actionName===null)
			$coord->actionName='default:index';
		if(jelix_scan_action_sel($sel,$this,$coord->actionName)){
			if($this->module=='#'){
				$this->module=$coord->moduleName;
			}
			elseif($this->module==''){
				$this->module=jApp::getCurrentModule();
			}
			if($this->request==''||!$enableRequestPart)
				$this->request=$coord->request->type;
			$this->_createPath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	protected function _createPath(){
		$conf=jApp::config();
		if(isset($conf->_modulesPathList[$this->module])){
			$p=$conf->_modulesPathList[$this->module];
		}else if($this->forUrl&&isset($conf->_externalModulesPathList[$this->module])){
			$p=$conf->_externalModulesPathList[$this->module];
		}
		else
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		$this->_path=$p.'controllers/'.$this->controller.'.'.$this->request.'.php';
	}
}
class jSelectorClass extends jSelectorModule{
	protected $type='class';
	protected $_dirname='classes/';
	protected $_suffix='.class.php';
	public $subpath='';
	public $className='';
	function __construct($sel){
		if(jelix_scan_class_sel($sel,$this)){
			if($this->module==''){
				$this->module=jApp::getCurrentModule();
			}
			$this->_createPath();
			$this->_createCachePath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.$this->subpath.$this->className.$this->_suffix;
		if(!file_exists($this->_path)||strpos($this->subpath,'..')!==false){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),$this->type));
		}
	}
	protected function _createCachePath(){
		$this->_cachePath='';
	}
	public function toString($full=false){
		if($full)
			return $this->type.':'.$this->module.'~'.$this->subpath.$this->className;
		else
			return $this->module.'~'.$this->subpath.$this->className;
	}
}
class jSelectorDao extends jSelectorModule{
	protected $type='dao';
	public $driver;
	protected $_dirname='daos/';
	protected $_suffix='.dao.xml';
	protected $_where;
	function __construct($sel,$driver,$isprofile=true){
		if($isprofile){
			$p=jProfiles::get('jdb',$driver);
			if($p['driver']=='pdo'){
				$this->driver=substr($p['dsn'],0,strpos($p['dsn'],':'));
			}
			else{
				$this->driver=$p['driver'];
			}
		}
		else{
			$this->driver=$driver;
		}
		$this->_compiler='jDaoCompiler';
		$this->_compilerPath=JELIX_LIB_PATH.'dao/jDaoCompiler.class.php';
		parent::__construct($sel);
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$overloadedPath=jApp::varPath('overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix);
		if(is_readable($overloadedPath)){
			$this->_path=$overloadedPath;
			$this->_where='overloaded/';
			return;
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
		if(!is_readable($this->_path)){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"dao"));
		}
		$this->_where='modules/';
	}
	protected function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/daos/'.$this->_where.$this->module.'~'.$this->resource.'~'.$this->driver.'_15'.$this->_cacheSuffix);
	}
	public function getDaoClass(){
		return 'cDao_'.$this->module.'_Jx_'.$this->resource.'_Jx_'.$this->driver;
	}
	public function getDaoRecordClass(){
		return 'cDaoRecord_'.$this->module.'_Jx_'.$this->resource.'_Jx_'.$this->driver;
	}
}
class jSelectorDaoRecord extends jSelectorModule{
	protected $type='daorecord';
	protected $_dirname='daos/';
	protected $_suffix='.daorecord.php';
	protected function _createCachePath(){
		$this->_cachePath='';
	}
}
class jSelectorForm extends jSelectorModule{
	protected $type='form';
	protected $_where;
	protected $_dirname='forms/';
	protected $_suffix='.form.xml';
	function __construct($sel){
		$this->_compiler='jFormsCompiler';
		$this->_compilerPath=JELIX_LIB_PATH.'forms/jFormsCompiler.class.php';
		parent::__construct($sel);
	}
	public function getClass(){
		return 'cForm_'.$this->module.'_Jx_'.$this->resource;
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString(true));
		}
		$overloadedPath=jApp::varPath('overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix);
		if(is_readable($overloadedPath)){
			$this->_path=$overloadedPath;
			$this->_where='overloaded/';
			return;
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
		if(!is_readable($this->_path)){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),$this->type));
		}
		$this->_where='modules/';
	}
	protected function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/'.$this->_dirname.$this->_where.$this->module.'~'.$this->resource.'_15'.$this->_cacheSuffix);
	}
	public function getCompiledBuilderFilePath($type){
		return jApp::tempPath('compiled/'.$this->_dirname.$this->_where.$this->module.'~'.$this->resource.'_builder_'.$type.$this->_cacheSuffix);
	}
}
class jSelectorIface extends jSelectorClass{
	protected $type='iface';
	protected $_dirname='classes/';
	protected $_suffix='.iface.php';
}
class jSelectorLoc extends jSelectorModule{
	protected $type='loc';
	public $fileKey='';
	public $messageKey='';
	public $locale='';
	public $charset='';
	public $_compiler='jLocalesCompiler';
	protected $_where;
	function __construct($sel,$locale=null,$charset=null){
		if($locale===null){
			$locale=jApp::config()->locale;
		}
		if($charset===null){
			$charset=jApp::config()->charset;
		}
		if(strpos($locale,'_')===false){
			$locale=jLocale::langToLocale($locale);
		}
		$this->locale=$locale;
		$this->charset=$charset;
		$this->_suffix='.'.$charset.'.properties';
		$this->_compilerPath=JELIX_LIB_CORE_PATH.'jLocalesCompiler.class.php';
		if(jelix_scan_locale_sel($sel,$this)){
			if($this->module==''){
				$this->module=jApp::getCurrentModule();
			}
			$this->_createPath();
			$this->_createCachePath();
		}
		else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			if($this->module=='jelix')
				throw new Exception('jelix module is not enabled !!');
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$locales=array($this->locale);
		$lang=substr($this->locale,0,strpos($this->locale,'_'));
		$generic_locale=$lang.'_'.strtoupper($lang);
		if($this->locale!==$generic_locale)
			$locales[]=$generic_locale;
		foreach($locales as $locale){
			$overloadedPath=jApp::varPath('overloads/'.$this->module.'/locales/'.$locale.'/'.$this->resource.$this->_suffix);
			if(is_readable($overloadedPath)){
				$this->_path=$overloadedPath;
				$this->_where='overloaded/';
				$this->_cacheSuffix='.'.$locale.'.'.$this->charset.'.php';
				return;
			}
			$localesPath=jApp::varPath('locales/'.$locale.'/'.$this->module.'/locales/'.$this->resource.$this->_suffix);
			if(is_readable($localesPath)){
				$this->_path=$localesPath;
				$this->_where='locales/';
				$this->_cacheSuffix='.'.$locale.'.'.$this->charset.'.php';
				return;
			}
			$path=jApp::config()->_modulesPathList[$this->module].'locales/'.$locale.'/'.$this->resource.$this->_suffix;
			if(is_readable($path)){
				$this->_where='modules/';
				$this->_path=$path;
				$this->_cacheSuffix='.'.$locale.'.'.$this->charset.'.php';
				return;
			}
		}
		if($this->toString()=='jelix~errors.selector.invalid.target'){
			$l='en_US';
			$c='UTF-8';
		}
		else{
			$l=null;
			$c=null;
		}
		throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"locale"),1,$l,$c);
	}
	protected function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/locales/'.$this->_where.$this->module.'~'.$this->resource.$this->_cacheSuffix);
	}
	public function toString($full=false){
		if($full)
			return $this->type.':'.$this->module.'~'.$this->fileKey.'.'.$this->messageKey;
		else
			return $this->module.'~'.$this->fileKey.'.'.$this->messageKey;
	}
}
class jSelectorTpl extends jSelectorModule{
	protected $type='tpl';
	protected $_dirname='templates/';
	protected $_suffix='.tpl';
	protected $_where;
	public $outputType='';
	public $trusted=true;
	public $userModifiers=array();
	public $userFunctions=array();
	function __construct($sel,$outputtype='',$trusted=true){
		if($outputtype==''){
			if(jApp::coord()->response)
				$this->outputType=jApp::coord()->response->getFormatType();
			else
				$this->outputType=jApp::coord()->request->defaultResponseType;
		}else
			$this->outputType=$outputtype;
		$this->trusted=$trusted;
		$this->_compiler='jTplCompiler';
		$this->_compilerPath=JELIX_LIB_PATH.'tpl/jTplCompiler.class.php';
		parent::__construct($sel);
	}
	protected function _createPath(){
		if(!isset(jApp::config()->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$path=$this->module.'/'.$this->resource;
		$lpath=$this->module.'/'.jApp::config()->locale.'/'.$this->resource;
		if(($theme=jApp::config()->theme)!='default'){
			$this->_where='themes/'.$theme.'/'.$lpath;
			$this->_path=jApp::varPath($this->_where.'.tpl');
			if(is_readable($this->_path)){
				return;
			}
			$this->_where='themes/'.$theme.'/'.$path;
			$this->_path=jApp::varPath($this->_where.'.tpl');
			if(is_readable($this->_path)){
				return;
			}
		}
		$this->_where='themes/default/'.$lpath;
		$this->_path=jApp::varPath($this->_where.'.tpl');
		if(is_readable($this->_path)){
			return;
		}
		$this->_where='themes/default/'.$path;
		$this->_path=jApp::varPath($this->_where.'.tpl');
		if(is_readable($this->_path)){
			return;
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.jApp::config()->locale.'/'.$this->resource.'.tpl';
		if(is_readable($this->_path)){
			$this->_where='modules/'.$lpath;
			return;
		}
		$this->_path=jApp::config()->_modulesPathList[$this->module].$this->_dirname.$this->resource.'.tpl';
		if(is_readable($this->_path)){
			$this->_where='modules/'.$path;
			return;
		}
		throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"template"));
	}
	protected function _createCachePath(){
		$this->_cachePath=jApp::tempPath('compiled/templates/'.$this->_where.'_'.$this->outputType.($this->trusted?'_t':'').'_15'.$this->_cacheSuffix);
	}
}
class jSelectorZone extends jSelectorModule{
	protected $type='zone';
	protected $_dirname='zones/';
	protected $_suffix='.zone.php';
	protected function _createCachePath(){
		$this->_cachePath='';
	}
}
class jSelectorSimpleFile implements jISelector{
	protected $type='simplefile';
	public $file='';
	protected $_path;
	protected $_basePath='';
	function __construct($sel){
		if(preg_match("/^([\w\.\/]+)$/",$sel,$m)){
			$this->file=$m[1];
			$this->_path=$this->_basePath.$m[1];
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	public function getPath(){
		return $this->_path;
	}
	public function toString($full=false){
		if($full)
			return $this->type.':'.$this->file;
		else
			return $this->file;
	}
	public function getCompiler(){return null;}
	public function useMultiSourceCompiler(){return false;}
	public function getCompiledFilePath(){return '';}
}
class jSelectorVar extends jSelectorSimpleFile{
	protected $type='var';
	function __construct($sel){
		$this->_basePath=jApp::varPath();
		parent::__construct($sel);
	}
}
class jSelectorCfg extends jSelectorSimpleFile{
	protected $type='cfg';
	function __construct($sel){
		$this->_basePath=jApp::configPath();
		parent::__construct($sel);
	}
}
class jSelectorTmp extends jSelectorSimpleFile{
	protected $type='tmp';
	function __construct($sel){
		$this->_basePath=jApp::tempPath();
		parent::__construct($sel);
	}
}
class jSelectorLog extends jSelectorSimpleFile{
	protected $type='log';
	function __construct($sel){
		$this->_basePath=jApp::logPath();
		parent::__construct($sel);
	}
}
class jSelectorLib extends jSelectorSimpleFile{
	protected $type='lib';
	function __construct($sel){
		$this->_basePath=LIB_PATH;
		parent::__construct($sel);
	}
}
abstract class jUrlBase{
	public $params=array();
	public function setParam($name,$value){
		$this->params[$name]=$value;
	}
	public function delParam($name){
		if(array_key_exists($name,$this->params))
			unset($this->params[$name]);
	}
	public function getParam($name,$defaultValue=null){
		return array_key_exists($name,$this->params)? $this->params[$name] :$defaultValue;
	}
	public function clearParam(){
		$this->params=array();
	}
	abstract public function toString($forxml=false);
	public function __toString(){
		return $this->toString();
	}
}
class jUrlAction extends jUrlBase{
	public $requestType='';
	function __construct($params=array(),$request=''){
		$this->params=$params;
		if($request==''){
			$this->requestType=jApp::coord()->request->type;
		}
		else
			$this->requestType=$request;
	}
	public function toString($forxml=false){
		return $this->toUrl()->toString($forxml);
	}
	public function toUrl(){
		return jUrl::getEngine()->create($this);
	}
}
class jUrl extends jUrlBase{
	const STRING=0;
	const XMLSTRING=1;
	const JURL=2;
	const JURLACTION=3;
	public $scriptName;
	public $pathInfo='';
	function __construct($scriptname='',$params=array(),$pathInfo=''){
		$this->params=$params;
		$this->scriptName=$scriptname;
		$this->pathInfo=$pathInfo;
	}
	public function toString($forxml=false){
		return $this->getPath().$this->getQuery($forxml);
	}
	public function getPath(){
		$url=$this->scriptName;
		if(substr($this->scriptName,-1)=='/')
			$url.=ltrim($this->pathInfo,'/');
		else
			$url.=$this->pathInfo;
		return $url;
	}
	public function getQuery($forxml=false){
		if(count($this->params)>0){
			$q=http_build_query($this->params,'',($forxml?'&amp;':'&'));
			if(!$q)
				return '';
			if(strpos($q,'%3A')!==false)
				$q=str_replace('%3A',':',$q);
			return '?'.$q;
		}
		return '';
	}
	static function getCurrentUrl($forxml=false,$full=false){
		$req=jApp::coord()->request;
		$sel=$req->module.'~'.$req->action;
		if($full){
			$url=self::getFull($sel,$req->params,($forxml?self::XMLSTRING:self::STRING));
		}
		else{
			$url=self::get($sel,$req->params,($forxml?self::XMLSTRING:self::STRING));
		}
		return $url;
	}
	static function appendToUrlString($url,$params=array(),$forxml=false){
		$q=http_build_query($params,'',($forxml?'&amp;':'&'));
		if(strpos($q,'%3A')!==false)
			$q=str_replace('%3A',':',$q);
		if((($pos=strpos($url,'?'))!==false)&&($pos!==(strlen($url)-1))){
			return $url .($forxml ? '&amp;' : '&').$q;
		}else{
			return $url . '?'.$q;
		}
	}
	static function get($actSel,$params=array(),$what=0){
		$sel=new jSelectorAct($actSel,true,true);
		$params['module']=$sel->module;
		$params['action']=$sel->resource;
		$ua=new jUrlAction($params,$sel->request);
		if($what==3)return $ua;
		$url=jUrl::getEngine()->create($ua);
		if($what==2)return $url;
		return $url->toString($what!=0);
	}
	static function getFull($actSel,$params=array(),$what=0,$domainName=null){
		$domain='';
		$req=jApp::coord()->request;
		$url=self::get($actSel,$params,($what!=self::XMLSTRING?self::STRING:$what));
		if(!preg_match('/^http/',$url)){
			if($domainName){
				$domain=$domainName;
				if(!preg_match('/^http/',$domainName))
					$domain=$req->getProtocol(). $domain;
			}
			else{
				$domain=$req->getServerURI();
			}
			if($domain==''){
				throw new jException('jelix~errors.urls.domain.void');
			}
		}
		else if($domainName!=''){
			$url=str_replace($req->getDomainName(),$domainName,$url);
		}
		return $domain.$url;
	}
	static function parse($scriptNamePath,$pathinfo,$params){
		return jUrl::getEngine()->parse($scriptNamePath,$pathinfo,$params);
	}
	static function escape($str,$highlevel=false){
		static $url_escape_from=null;
		static $url_escape_to=null;
		if($highlevel){
			if($url_escape_from==null){
				$url_escape_from=explode(' ',jLocale::get('jelix~format.url_escape_from'));
				$url_escape_to=explode(' ',jLocale::get('jelix~format.url_escape_to'));
			}
			$str=str_replace($url_escape_from,$url_escape_to,$str);
			$str=preg_replace("/([^\w])/"," ",$str);
			$str=preg_replace("/( +)/","-",trim($str));
			$str=urlencode(strtolower($str));
			return $str;
		}else{
			return urlencode(str_replace(array('-',' '),array('--','-'),$str));
		}
	}
	static function unescape($str){
		return strtr($str,array('--'=>'-','-'=>' '));
	}
	static function getEngine($reset=false){
		static $engine=null;
		if($engine===null||$reset){
			$name=jApp::config()->urlengine['engine'];
			$engine=jApp::loadPlugin($name,'urls','.urls.php',$name.'UrlEngine');
			if(is_null($engine))
				throw new jException('jelix~errors.urls.engine.notfound',$name);
		}
		return $engine;
	}
	public static function getRootUrl($ressourceType){
		$rootUrl=jUrl::getRootUrlRessourceValue($ressourceType);
		if($rootUrl!==null){
			if(substr($rootUrl,0,7)!=='http://'&&substr($rootUrl,0,8)!=='https://'
				&&substr($rootUrl,0,1)!=='/'){
					$rootUrl=jApp::urlBasePath(). $rootUrl;
			}
		}else{
			$rootUrl=jApp::urlBasePath();
		}
		return $rootUrl;
	}
	public static function getRootUrlRessourceValue($ressourceType){
		if(! isset(jApp::config()->rootUrls[$ressourceType])){
			return null;
		}else{
			return jApp::config()->rootUrls[$ressourceType];
		}
	}
}
class jCoordinator{
	public $plugins=array();
	public $response=null;
	public $request=null;
	public $action=null;
	public $originalAction=null;
	public $moduleName;
	public $actionName;
	protected $errorMessage=null;
	function __construct($configFile='',$enableErrorHandler=true){
		if($configFile)
			jApp::loadConfig($configFile,$enableErrorHandler);
		$this->_loadPlugins();
	}
	private function _loadPlugins(){
		$config=jApp::config();
		foreach($config->coordplugins as $name=>$conf){
			if(strpos($name,'.')!==false)
				continue;
			if($conf=='1'){
				$confname='coordplugin_'.$name;
				if(isset($config->$confname))
					$conf=$config->$confname;
				else
					$conf=array();
			}
			else{
				$conff=jApp::configPath($conf);
				if(false===($conf=parse_ini_file($conff,true)))
					throw new Exception("Error in a plugin configuration file -- plugin: $name  file: $conff",13);
			}
			include_once($config->_pluginsPathList_coord[$name].$name.'.coord.php');
			$class=$name.'CoordPlugin';
			if(isset($config->coordplugins[$name.'.name']))
				$name=$config->coordplugins[$name.'.name'];
			$this->plugins[strtolower($name)]=new $class($conf);
		}
	}
	protected function setRequest($request){
		$config=jApp::config();
		$this->request=$request;
		if($config->enableErrorHandler){
			set_error_handler(array($this,'errorHandler'));
			set_exception_handler(array($this,'exceptionHandler'));
			foreach(jBasicErrorHandler::$initErrorMessages as $msg){
				jLog::log($msg,$msg->getCategory());
			}
		}
		$this->request->init();
		list($this->moduleName,$this->actionName)=$request->getModuleAction();
		jApp::pushCurrentModule($this->moduleName);
		$this->action=
		$this->originalAction=new jSelectorActFast($this->request->type,$this->moduleName,$this->actionName);
		if($config->modules[$this->moduleName.'.access'] < 2){
			throw new jException('jelix~errors.module.untrusted',$this->moduleName);
		}
	}
	public function process($request=null){
		try{
			if($request)
				$this->setRequest($request);
			jSession::start();
			$ctrl=$this->getController($this->action);
		}
		catch(jException $e){
			$config=jApp::config();
			if($config->urlengine['notfoundAct']==''){
				throw $e;
			}
			if(!jSession::isStarted()){
				jSession::start();
			}
			try{
				$this->action=new jSelectorAct($config->urlengine['notfoundAct']);
				$ctrl=$this->getController($this->action);
			}
			catch(jException $e2){
				throw $e;
			}
		}
		jApp::pushCurrentModule($this->moduleName);
		if(count($this->plugins)){
			$pluginparams=array();
			if(isset($ctrl->pluginParams['*'])){
				$pluginparams=$ctrl->pluginParams['*'];
			}
			if(isset($ctrl->pluginParams[$this->action->method])){
				$pluginparams=array_merge($pluginparams,$ctrl->pluginParams[$this->action->method]);
			}
			foreach($this->plugins as $name=>$obj){
				$result=$this->plugins[$name]->beforeAction($pluginparams);
				if($result){
					$this->action=$result;
					jApp::popCurrentModule();
					jApp::pushCurrentModule($result->module);
					$this->moduleName=$result->module;
					$this->actionName=$result->resource;
					$ctrl=$this->getController($this->action);
					break;
				}
			}
		}
		$this->response=$ctrl->{$this->action->method}();
		if($this->response==null){
			throw new jException('jelix~errors.response.missing',$this->action->toString());
		}
		foreach($this->plugins as $name=>$obj){
			$this->plugins[$name]->beforeOutput();
		}
		$this->response->output();
		foreach($this->plugins as $name=>$obj){
			$this->plugins[$name]->afterProcess();
		}
		jApp::popCurrentModule();
		jSession::end();
	}
	protected function getController($selector){
		$ctrlpath=$selector->getPath();
		if(isset($_SERVER['HTTP_REFERER'])){
			$referer=' REFERER:'.$_SERVER['HTTP_REFERER'];
		}
		else{
			$referer='';
		}
		if(!file_exists($ctrlpath)){
			throw new jException('jelix~errors.ad.controller.file.unknown',array($this->actionName,$ctrlpath.$referer));
		}
		require_once($ctrlpath);
		$class=$selector->getClass();
		if(!class_exists($class,false)){
			throw new jException('jelix~errors.ad.controller.class.unknown',array($this->actionName,$class,$ctrlpath.$referer));
		}
		$ctrl=new $class($this->request);
		if($ctrl instanceof jIRestController){
			$selector->method=strtolower($_SERVER['REQUEST_METHOD']);
		}
		elseif(!is_callable(array($ctrl,$selector->method))){
			throw new jException('jelix~errors.ad.controller.method.unknown',array($this->actionName,$selector->method,$class,$ctrlpath.$referer));
		}
		if(property_exists($ctrl,'sensitiveParameters')){
			$config=jApp::config();
			$config->error_handling['sensitiveParameters']=array_merge($config->error_handling['sensitiveParameters'],$ctrl->sensitiveParameters);
		}
		return $ctrl;
	}
	public function execOriginalAction(){
		if(!$this->originalAction){
			return false;
		}
		return $this->originalAction->isEqualTo($this->action);
	}
	function errorHandler($errno,$errmsg,$filename,$linenum,$errcontext){
		if(error_reporting()==0)
			return;
		if(preg_match('/^\s*\((\d+)\)(.+)$/',$errmsg,$m)){
			$code=$m[1];
			$errmsg=$m[2];
		}
		else{
			$code=1;
		}
		if(!isset(jBasicErrorHandler::$errorCode[$errno])){
			$errno=E_ERROR;
		}
		$codestr=jBasicErrorHandler::$errorCode[$errno];
		$trace=debug_backtrace();
		array_shift($trace);
		$this->handleError($codestr,$errno,$errmsg,$filename,$linenum,$trace);
	}
	function exceptionHandler($e){
		$this->handleError('error',$e->getCode(),$e->getMessage(),$e->getFile(),
						$e->getLine(),$e->getTrace());
	}
	public function handleError($type,$code,$message,$file,$line,$trace){
		$errorLog=new jLogErrorMessage($type,$code,$message,$file,$line,$trace);
		$errorLog->setFormat(jApp::config()->error_handling['messageLogFormat']);
		jLog::log($errorLog,$type);
		if($type!='error')
			return;
		$this->errorMessage=$errorLog;
		while(ob_get_level()&&@ob_end_clean());
		$resp=$this->request->getErrorResponse($this->response);
		$resp->outputErrors();
		jSession::end();
		exit(1);
	}
	public function getGenericErrorMessage(){
		$msg=jApp::config()->error_handling['errorMessage'];
		if($this->errorMessage)
			$code=$this->errorMessage->getCode();
		else $code='';
		return str_replace('%code%',$code,$msg);
	}
	public function getErrorMessage(){
		return $this->errorMessage;
	}
	public function getPlugin($pluginName,$required=true){
		$pluginName=strtolower($pluginName);
		if(isset($this->plugins[$pluginName])){
			$plugin=$this->plugins[$pluginName];
		}else{
			if($required){
				throw new jException('jelix~errors.plugin.unregister',$pluginName);
			}
			$plugin=null;
		}
		return $plugin;
	}
	public function isPluginEnabled($pluginName){
		return isset($this->plugins[strtolower($pluginName)]);
	}
}
interface jIRestController{
	public function get();
	public function post();
	public function put();
	public function delete();
}
abstract class jController{
	public $pluginParams=array();
	public $sensitiveParameters=array();
	protected $request;
	function __construct($request){
		$this->request=$request;
	}
	protected function param($parName,$parDefaultValue=null,$useDefaultIfEmpty=false){
		return $this->request->getParam($parName,$parDefaultValue,$useDefaultIfEmpty);
	}
	protected function intParam($parName,$parDefaultValue=null,$useDefaultIfEmpty=false){
		$value=$this->request->getParam($parName,$parDefaultValue,$useDefaultIfEmpty);
		if(is_numeric($value))
			return intval($value);
		else
			return null;
	}
	protected function floatParam($parName,$parDefaultValue=null,$useDefaultIfEmpty=false){
		$value=$this->request->getParam($parName,$parDefaultValue,$useDefaultIfEmpty);
		if(is_numeric($value))
			return floatval($value);
		else
			return null;
	}
	protected function boolParam($parName,$parDefaultValue=null,$useDefaultIfEmpty=false){
		$value=$this->request->getParam($parName,$parDefaultValue,$useDefaultIfEmpty);
		if($value=="true"||$value=="1"||$value=="on"||$value=="yes")
			return true;
		elseif($value=="false"||$value=="0"||$value=="off"||$value=="no")
			return false;
		else
			return null;
	}
	protected function params(){return $this->request->params;}
	protected function getResponse($name='',$useOriginal=false){
		return $this->request->getResponse($name,$useOriginal);
	}
}
abstract class jRequest{
	public $params=array();
	public $type;
	public $defaultResponseType='';
	public $authorizedResponseClass='';
	public $urlScriptPath;
	public $urlScriptName;
	public $urlScript;
	public $urlPathInfo;
	public $module='';
	public $action='';
	function __construct(){}
	public function init(){
		$this->_initUrlData();
		$this->_initParams();
	}
	abstract protected function _initParams();
	protected function _initUrlData(){
		$conf=&jApp::config()->urlengine;
		$this->urlScript=$conf['urlScript'];
		$this->urlScriptPath=$conf['urlScriptPath'];
		$this->urlScriptName=$conf['urlScriptName'];
		$piiqp=$conf['pathInfoInQueryParameter'];
		if($piiqp){
			if(isset($_GET[$piiqp])){
				$pathinfo=$_GET[$piiqp];
				unset($_GET[$piiqp]);
			}else
				$pathinfo='';
		}else if(isset($_SERVER['PATH_INFO'])){
			$pathinfo=$_SERVER['PATH_INFO'];
		}else if(isset($_SERVER['ORIG_PATH_INFO'])){
			$pathinfo=$_SERVER['ORIG_PATH_INFO'];
		}else
			$pathinfo='';
		if($pathinfo==$this->urlScript){
			$pathinfo='';
		}
		if(jApp::config()->isWindows&&$pathinfo&&strpos($pathinfo,$this->urlScript)!==false){
			$pathinfo=substr($pathinfo,strlen($this->urlScript));
		}
		$this->urlPathInfo=$pathinfo;
	}
	public function getModuleAction(){
		$conf=jApp::config();
		if(isset($this->params['module'])&&trim($this->params['module'])!=''){
			$this->module=$this->params['module'];
		}
		else{
			$this->module=$conf->startModule;
		}
		if(isset($this->params['action'])&&trim($this->params['action'])!=''){
			$this->action=$this->params['action'];
		}
		else{
			if($this->module==$conf->startModule)
				$this->action=$conf->startAction;
			else{
				$this->action='default:index';
			}
		}
		return array($this->module,$this->action);
	}
	public function getParam($name,$defaultValue=null,$useDefaultIfEmpty=false){
		if(isset($this->params[$name])){
			if($useDefaultIfEmpty&&trim($this->params[$name])==''){
				return $defaultValue;
			}else{
				return $this->params[$name];
			}
		}else{
			return $defaultValue;
		}
	}
	public function isAllowedResponse($response){
		return(($response instanceof $this->authorizedResponseClass)
				||($c=get_class($response))=='jResponseRedirect'
				||$c=='jResponseRedirectUrl'
				);
	}
	public function getResponse($type='',$useOriginal=false){
		if($type==''){
			$type=$this->defaultResponseType;
		}
		if($useOriginal)
			$responses=&jApp::config()->_coreResponses;
		else
			$responses=&jApp::config()->responses;
		$coord=jApp::coord();
		if(!isset($responses[$type])){
			if($coord->action){
				$action=$coord->action->resource;
				$path=$coord->action->getPath();
			}
			else{
				$action=$coord->moduleName.'~'.$coord->actionName;
				$path='';
			}
			if($type==$this->defaultResponseType)
				throw new jException('jelix~errors.default.response.type.unknown',array($action,$type));
			else
				throw new jException('jelix~errors.ad.response.type.unknown',array($action,$type,$path));
		}
		$respclass=$responses[$type];
		$path=$responses[$type.'.path'];
		if(!class_exists($respclass,false))
			require($path);
		$response=new $respclass();
		if(!$this->isAllowedResponse($response)){
			throw new jException('jelix~errors.ad.response.type.notallowed',array($coord->action->resource,$type,$coord->action->getPath()));
		}
		$coord->response=$response;
		return $response;
	}
	public function getErrorResponse($currentResponse){
	try{
		return $this->getResponse('',true);
	}
	catch(Exception $e){
		require_once(JELIX_LIB_CORE_PATH.'response/jResponseText.class.php');
		return new jResponseText();
	}
	}
	function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']){
			$list=preg_split('/[\s,]+/',$_SERVER['HTTP_X_FORWARDED_FOR']);
			$list=array_reverse($list);
			$lastIp='';
			foreach($list as $ip){
				$ip=trim($ip);
				if(preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/',$ip,$m)){
					if($m[1]=='10'||$m[1]=='010'
						||($m[1]=='172'&&(intval($m[2])& 240==16))
						||($m[1]=='192'&&$m[2]=='168'))
						break;
					$lastIp=$ip;
				}
				elseif(preg_match('/^(?:[a-f0-9]{1,4})(?::(?:[a-f0-9]{1,4})){7}$/i',$ip)){
					$lastIp=$ip;
				}
			}
			if($lastIp)
				return $lastIp;
		}
		if(isset($_SERVER['HTTP_CLIENT_IP'])&&$_SERVER['HTTP_CLIENT_IP']){
			return  $_SERVER['HTTP_CLIENT_IP'];
		}else{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	function getProtocol(){
	return($this->isHttps()? 'https://':'http://');
	}
	function isHttps(){
		if(jApp::config()->urlengine['forceProxyProtocol']=='https'){
			return true;
		}
		return(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']&&$_SERVER['HTTPS']!='off');
	}
	function isAjax(){
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']))
		return($_SERVER['HTTP_X_REQUESTED_WITH']==="XMLHttpRequest");
	else
		return false;
	}
	function isPostMethod(){
		if(isset($_SERVER['REQUEST_METHOD'])){
			return($_SERVER['REQUEST_METHOD']==="POST");
		}else{
			return false;
		}
	}
	function getDomainName(){
	if(jApp::config()->domainName!=''){
		return jApp::config()->domainName;
	}
	elseif(isset($_SERVER['HTTP_HOST'])){
		if(($pos=strpos($_SERVER['HTTP_HOST'],':'))!==false)
			return substr($_SERVER['HTTP_HOST'],0,$pos);
		return $_SERVER['HTTP_HOST'];
	}
	elseif(isset($_SERVER['SERVER_NAME'])){
		return $_SERVER['SERVER_NAME'];
	}
	return '';
	}
	function getServerURI($forceHttps=null){
	if(($forceHttps===null&&$this->isHttps())||$forceHttps){
		$uri='https://';
	}
	else{
		$uri='http://';
	}
	$uri.=$this->getDomainName();
	$uri.=$this->getPort($forceHttps);
	return $uri;
	}
	function getPort($forceHttps=null){
	$isHttps=$this->isHttps();
	if($forceHttps===null)
		$https=$isHttps;
	else
		$https=$forceHttps;
	$forcePort=($https ? jApp::config()->forceHTTPSPort : jApp::config()->forceHTTPPort);
	if($forcePort===true){
		return '';
	}
	else if($forcePort){
		$port=$forcePort;
	}
	else if($isHttps!=$https||!isset($_SERVER['SERVER_PORT'])){
		return '';
	}else{
		$port=$_SERVER['SERVER_PORT'];
	}
	if(($port===NULL)||($port=='')||($https&&$port=='443')||(!$https&&$port=='80'))
		return '';
	return ':'.$port;
	}
	public function readHttpBody(){
		$input=file_get_contents('php://input');
		if(!isset($_SERVER['CONTENT_TYPE'])){
			return $input;
		}
		$contentType=$_SERVER['CONTENT_TYPE'];
		$values=array();
		if(strpos($contentType,'application/x-www-form-urlencoded')===0){
			parse_str($input,$values);
			return $values;
		}
		if(strpos($contentType,'multipart/form-data')===0){
			return self::parseMultipartBody($contentType,$input);
		}
		if(jApp::config()->enableRequestBodyJSONParsing&&strpos($contentType,'application/json')===0){
			return json_decode($input,true);
		}
		return $input;
	}
	public static function parseMultipartBody($contentType,$input){
		$values=array();
		if(!preg_match('/boundary=([^\\s]+)/',$contentType,$m)){
			return $values;
		}
		$parts=preg_split("/\r\n--" . preg_quote($m[1])."/",$input);
		foreach($parts as $part){
			if(trim($part)==''||$part=='--')
				continue;
			list($header,$value)=explode("\r\n\r\n",$part,2);
			$value=rtrim($value);
			if(preg_match('/content-disposition\:\\s*form-data\;\\s*name="([^"]+)"(\;\\s*filename="([^"]+)")?/i',$header,$m)){
				$name=$m[1];
				if(isset($m[2])&&$m[3]!=''){
					$values=array($m[3],$value);
				}
				if(preg_match("/^([^\\[]+)\\[([^\\]]*)\\]$/",$name,$nm)){
					$name=$nm[1];
					$index=$nm[2];
					if(!isset($values[$name])||!is_array($values[$name])){
						$values[$name]=array();
					}
					if($index===""){
						$values[$name][]=$value;
					}
					else{
						$values[$name][$index]=$value;
					}
				}
				else{
					$values[$name]=$value;
				}
			}
		}
		return $values;
	}
	private $_headers=null;
	private function _generateHeaders(){
	if(is_null($this->_headers)){
		if(function_exists('apache_response_headers')){
			$this->_headers=apache_request_headers();
		}
		else{
			$this->_headers=array();
			foreach($_SERVER as $key=>$value){
				if(substr($key,0,5)=="HTTP_"){
				$key=str_replace(" ","-",
						ucwords(strtolower(str_replace('_',' ',substr($key,5)))));
				$this->_headers[$key]=$value;
				}
			}
		}
	}
	}
	public function header($name){
	$this->_generateHeaders();
	if(isset($this->_headers[$name])){
		return $this->_headers[$name];
	}
	return null;
	}
	public function headers(){
	$this->_generateHeaders();
	return $this->_headers;
	}
}
abstract class jResponse{
	protected  $_type=null;
	protected $_httpHeaders=array();
	protected $_httpHeadersSent=false;
	protected $_httpStatusCode='200';
	protected $_httpStatusMsg='OK';
	protected $_outputOnlyHeaders=false;
	public $httpVersion='1.1';
	public $forcedHttpVersion=false;
	function __construct(){
		if(jApp::config()->httpVersion!=""){
			$this->httpVersion=jApp::config()->httpVersion;
			$this->forcedHttpVersion=true;
		}
	}
	abstract public function output();
	public function outputErrors(){
		if(isset($_SERVER['HTTP_ACCEPT'])&&strstr($_SERVER['HTTP_ACCEPT'],'text/html')){
			require_once(JELIX_LIB_CORE_PATH.'response/jResponseBasicHtml.class.php');
			$response=new jResponseBasicHtml();
			$response->outputErrors();
		}
		else{
			header("HTTP/{$this->httpVersion} 500 Internal jelix error");
			header('Content-type: text/plain');
			echo jApp::coord()->getGenericErrorMessage();
		}
	}
	public final function getType(){return $this->_type;}
	public function getFormatType(){return $this->_type;}
	public function addHttpHeader($htype,$hcontent,$overwrite=true){
		if(isset($this->_httpHeaders[$htype])){
			$val=$this->_httpHeaders[$htype];
			if($overwrite===-1){
				if(!is_array($val))
					$this->_httpHeaders[$htype]=array($val,$hcontent);
				else
					$this->_httpHeaders[$htype][]=$hcontent;
				return;
			}
			else if(!$overwrite){
				return;
			}
		}
		$this->_httpHeaders[$htype]=$hcontent;
	}
	public function clearHttpHeaders(){
		$this->_httpHeaders=array();
		$this->_httpStatusCode='200';
		$this->_httpStatusMsg='OK';
	}
	public function setHttpStatus($code,$msg){$this->_httpStatusCode=$code;$this->_httpStatusMsg=$msg;}
	protected function sendHttpHeaders(){
		header((isset($_SERVER['SERVER_PROTOCOL'])&&!$this->forcedHttpVersion ?
						$_SERVER['SERVER_PROTOCOL'] :
						'HTTP/'.$this->httpVersion).
				' '.$this->_httpStatusCode.' '.$this->_httpStatusMsg);
		foreach($this->_httpHeaders as $ht=>$hc){
			if(is_array($hc)){
				foreach($hc as $val){
					header($ht.': '.$val);
				}
			}
			else
				header($ht.': '.$hc);
		}
		$this->_httpHeadersSent=true;
	}
	protected function _normalizeDate($date){
		if($date instanceof jDateTime){
			return gmdate('D, d M Y H:i:s \G\M\T',$date->toString(jDateTime::TIMESTAMP_FORMAT));
		}
		elseif($date instanceof DateTime){
			return gmdate('D, d M Y H:i:s \G\M\T',$date->getTimestamp());
		}
		else{
			return gmdate('D, d M Y H:i:s \G\M\T',strtotime($date));
		}
	}
	protected function _checkRequestType(){
		$allowedTypes=array('GET','HEAD');
		if(in_array($_SERVER['REQUEST_METHOD'],$allowedTypes)){
			return true;
		}
		else{
			trigger_error(jLocale::get('jelix~errors.rep.bad.request.method'),E_USER_WARNING);
			return false;
		}
	}
	public function cleanCacheHeaders(){
		$toClean=array('Cache-Control','Expires','Pragma');
		foreach($toClean as $h){
			unset($this->_httpHeaders[$h]);
			$this->addHttpHeader($h,'');
		}
	}
	public function setExpires($date,$cleanCacheHeader=true){
		if(!$this->_checkRequestType())
			return;
		if($cleanCacheHeader)
			$this->cleanCacheHeaders();
		$date=$this->_normalizeDate($date);
		$this->addHttpHeader('Expires',$date);
	}
	public function setLifetime($time,$sharedCache=false,$cleanCacheHeader=true){
		if(!$this->_checkRequestType())
			return;
		if($cleanCacheHeader)
			$this->cleanCacheHeaders();
		$type=$sharedCache ? 'public' : 'private';
		$this->addHttpHeader('Cache-Control',$type.', '.($sharedCache ? 's-' : '').'maxage='.$time);
	}
	public function isValidCache($dateLastModified=null,$etag=null,$cleanCacheHeader=true){
		if(!$this->_checkRequestType())
			return false;
		$notModified=false;
		if($cleanCacheHeader)
			$this->cleanCacheHeaders();
		if($dateLastModified!=null){
			$dateLastModified=$this->_normalizeDate($dateLastModified);
			$lastModified=jApp::coord()->request->header('If-Modified-Since');
			if($lastModified!==null&&$lastModified==$dateLastModified){
				$notModified=true;
			}
			else{
				$this->addHttpHeader('Last-Modified',$dateLastModified);
			}
		}
		if($etag!=null){
			$headerEtag=jApp::coord()->request->header('If-None-Match');
			if($headerEtag!==null&&$etag==$headerEtag){
				$notModified=true;
			}
			else{
				$this->addHttpHeader('Etag',$etag);
			}
		}
		if($notModified){
			$this->_outputOnlyHeaders=true;
			$this->setHttpStatus(304,'Not Modified');
			$toClean=array('Allow','Content-Encoding','Content-Language','Content-Length','Content-MD5','Content-Type','Last-Modified','Etag');
			foreach($toClean as $h)
				unset($this->_httpHeaders[$h]);
		}
		return $notModified;
	}
}
class jBundle{
	public $fic;
	public $locale;
	protected $_loadedCharset=array();
	protected $_strings=array();
	public function __construct($file,$locale){
		$this->fic=$file;
		$this->locale=$locale;
	}
	public function get($key,$charset=null){
		if($charset==null){
			$charset=jApp::config()->charset;
		}
		if(!in_array($charset,$this->_loadedCharset)){
			$this->_loadLocales($charset);
		}
		if(isset($this->_strings[$charset][$key])){
			return $this->_strings[$charset][$key];
		}else{
			return null;
		}
	}
	protected function _loadLocales($charset){
		$this->_loadedCharset[]=$charset;
		$source=$this->fic->getPath();
		$cache=$this->fic->getCompiledFilePath();
		if(is_readable($cache)){
			$okcompile=true;
			if(jApp::config()->compilation['force']){
				$okcompile=false;
			}else{
				if(jApp::config()->compilation['checkCacheFiletime']){
					if(is_readable($source)&&filemtime($source)> filemtime($cache)){
						$okcompile=false;
					}
				}
			}
			if($okcompile){
				include($cache);
				$this->_strings[$charset]=$_loaded;
				return;
			}
		}
		$this->_loadResources($source,$charset);
		if(isset($this->_strings[$charset])){
			$content='<?php $_loaded= '.var_export($this->_strings[$charset],true).' ?>';
			jFile::write($cache,$content);
		}
	}
	protected function _loadResources($fichier,$charset){
		if(($f=@fopen($fichier,'r'))!==false){
			$utf8Mod=($charset=='UTF-8')?'u':'';
			$unbreakablespace=($charset=='UTF-8')?utf8_encode(chr(160)):chr(160);
			$escapedChars=array('\#','\n','\w','\S','\s');
			$unescape=array('#',"\n",' ',$unbreakablespace,' ');
			$multiline=false;
			$linenumber=0;
			$key='';
			while(!feof($f)){
				if($line=fgets($f)){
					$linenumber++;
					$line=rtrim($line);
					if($multiline){
						if(preg_match("/^\s*(.*)\s*(\\\\?)$/U".$utf8Mod,$line,$match)){
							$multiline=($match[2]=="\\");
							if(strlen($match[1])){
								$sp=preg_split('/(?<!\\\\)\#/',$match[1],-1,PREG_SPLIT_NO_EMPTY);
								$this->_strings[$charset][$key].=' '.str_replace($escapedChars,$unescape,trim($sp[0]));
							}else{
								$this->_strings[$charset][$key].=' ';
							}
						}else{
							throw new Exception('Syntaxe error in file properties '.$fichier.' line '.$linenumber,210);
						}
					}elseif(preg_match("/^\s*(.+)\s*=\s*(.*)\s*(\\\\?)$/U".$utf8Mod,$line,$match)){
						$key=$match[1];
						$multiline=($match[3]=="\\");
						$sp=preg_split('/(?<!\\\\)\#/',$match[2],-1,PREG_SPLIT_NO_EMPTY);
						if(count($sp)){
							$value=trim($sp[0]);
						}else{
							$value='';
						}
						$this->_strings[$charset][$key]=str_replace($escapedChars,$unescape,$value);
					}elseif(preg_match("/^\s*(\#.*)?$/",$line,$match)){
					}else{
						throw new Exception('Syntaxe error in file properties '.$fichier.' line '.$linenumber,211);
					}
				}
			}
			fclose($f);
		}else{
			throw new Exception('Cannot load the resource '.$fichier,212);
		}
	}
}
class jLocale{
	static $bundles=array();
	private function __construct(){}
	static function getCurrentLang(){
		$s=jApp::config()->locale;
		return substr($s,0,strpos($s,'_'));
	}
	static function getCurrentCountry(){
		$s=jApp::config()->locale;
		return substr($s,strpos($s,'_')+1);
	}
	static function get($key,$args=null,$locale=null,$charset=null){
		$config=jApp::config();
		try{
			$file=new jSelectorLoc($key,$locale,$charset);
		}
		catch(jExceptionSelector $e){
			if($e->getCode()==12)throw $e;
			if($locale===null)$locale=$config->locale;
			if($charset===null)$charset=$config->charset;
			if($locale!=$config->fallbackLocale&&$config->fallbackLocale){
				return jLocale::get($key,$args,$config->fallbackLocale,$charset);
			}
			else
				throw new Exception('(200)The given locale key "'.$key
								.'" is invalid (for charset '.$charset
								.', lang '.$locale.')');
		}
		$locale=$file->locale;
		$keySelector=$file->module.'~'.$file->fileKey;
		if(!isset(self::$bundles[$keySelector][$locale])){
			self::$bundles[$keySelector][$locale]=new jBundle($file,$locale);
		}
		$bundle=self::$bundles[$keySelector][$locale];
		$string=$bundle->get($file->messageKey,$file->charset);
		if($string===null){
			if($locale==$config->fallbackLocale){
				throw new Exception('(210)The given locale key "'.$file->toString().'" does not exists in the default lang and in the fallback lang for the '.$file->charset.' charset');
			}
			else if($locale==$config->locale){
				if($config->fallbackLocale)
					return jLocale::get($key,$args,$config->fallbackLocale,$charset);
				throw new Exception('(210)The given locale key "'.$file->toString().'" does not exists in the default lang for the '.$file->charset.' charset');
			}
			return jLocale::get($key,$args,$config->locale);
		}
		else{
			if($args!==null&&$args!==array()){
				$string=call_user_func_array('sprintf',array_merge(array($string),is_array($args)? $args : array($args)));
			}
			return $string;
		}
	}
	static function getCorrespondingLocale($l,$strictCorrespondance=false){
		if(strpos($l,'_')===false){
			$l=self::langToLocale($l);
		}
		if($l!=''){
			$avLoc=&jApp::config()->availableLocales;
			if(in_array($l,$avLoc)){
				return $l;
			}
			if($strictCorrespondance)
				return '';
			$l2=self::langToLocale(substr($l,0,strpos($l,'_')));
			if($l2!=$l&&in_array($l2,$avLoc)){
				return $l2;
			}
		}
		return '';
	}
	static function getPreferedLocaleFromRequest(){
		if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			return '';
		$languages=explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		foreach($languages as $bl){
			if(!preg_match("/^([a-zA-Z]{2,3})(?:[-_]([a-zA-Z]{2,3}))?(;q=[0-9]\\.[0-9])?$/",$bl,$match))
				continue;
			$l=strtolower($match[1]);
			if(isset($match[2]))
				$l.='_'.strtoupper($match[2]);
			$lang=self::getCorrespondingLocale($l);
			if($lang!='')
				return $lang;
		}
		return '';
	}
	static protected $langToLocale=null;
	static function langToLocale($lang){
		$conf=jApp::config();
		if(isset($conf->langToLocale[$lang]))
			return $conf->langToLocale[$lang];
		if(is_null(self::$langToLocale)){
			self::$langToLocale=@parse_ini_file(JELIX_LIB_CORE_PATH.'lang_to_locale.ini.php');
		}
		if(isset(self::$langToLocale[$lang])){
			return self::$langToLocale[$lang];
		}
		return '';
	}
}
interface jILogMessage{
	public function getCategory();
	public function getMessage();
	public function getFormatedMessage();
}
class jLogMessage implements jILogMessage{
	protected $category;
	protected $message;
	public function __construct($message,$category='default'){
		$this->category=$category;
		$this->message=$message;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getMessage(){
		return $this->message;
	}
	public function getFormatedMessage(){
		return $this->message;
	}
}
class jLogDumpMessage  extends jLogMessage{
	protected $label;
	public function __construct($obj,$label='',$category='default'){
		$this->message=var_export($obj,true);
		$this->category=$category;
		$this->label=$label;
	}
	public function getLabel(){
		return $this->label;
	}
	public function getFormatedMessage(){
		if($this->label){
			return $this->label.': '.$this->message;
		}
		return $this->message;
	}
}
class jLogErrorMessage implements jILogMessage{
	protected $category;
	protected $message;
	protected $file;
	protected $line;
	protected $trace;
	protected $code;
	protected $format='%date%\t%ip%\t[%code%]\t%msg%\t%file%\t%line%\n\t%url%\n%params%\n%trace%';
	public function __construct($category,$code,$message,$file,$line,$trace){
		$this->category=$category;
		$this->message=$message;
		$this->code=$code;
		$this->file=$file;
		$this->line=$line;
		$this->trace=$trace;
	}
	public function setFormat($format){
		$this->format=$format;
	}
	public function getCode(){
		return $this->code;
	}
	public function getCategory(){
		return $this->category;
	}
	public function getMessage(){
		return $this->message;
	}
	public function getFile(){
		return $this->file;
	}
	public function getLine(){
		return $this->line;
	}
	public function getTrace(){
		return $this->trace;
	}
	public function getFormatedMessage(){
		if(isset($_SERVER['REQUEST_URI']))
			$url=$_SERVER['REQUEST_URI'];
		elseif(isset($_SERVER['SCRIPT_NAME']))
			$url=$_SERVER['SCRIPT_NAME'];
		else
			$url='Unknow request';
		if(jApp::coord()&&($req=jApp::coord()->request)){
			$params=$this->sanitizeParams($req->params);
			$remoteAddr=$req->getIP();
		}
		else{
			$params=$this->sanitizeParams(isset($_GET)?$_GET:array());
			$remoteAddr=isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		}
		$traceLog="";
		foreach($this->trace as $k=>$t){
			$traceLog.="\n\t$k\t".(isset($t['class'])?$t['class'].$t['type']:'').$t['function']."()\t";
			$traceLog.=(isset($t['file'])?$t['file']:'[php]').' : '.(isset($t['line'])?$t['line']:'');
		}
		$httpReferer=isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER'] : 'Unknown referer';
		$messageLog=strtr($this->format,array(
			'%date%'=>@date("Y-m-d H:i:s"),
			'%typeerror%'=>$this->category,
			'%code%'=>$this->code,
			'%msg%'=>$this->message,
			'%ip%'=>$remoteAddr,
			'%url%'=>$url,
			'%referer%'=>$httpReferer,
			'%params%'=>$params,
			'%file%'=>$this->file,
			'%line%'=>$this->line,
			'%trace%'=>$traceLog,
			'%http_method%'=>isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'Unknown method',
			'\t'=>"\t",
			'\n'=>"\n"
		));
		return $messageLog;
	}
	protected function sanitizeParams($params){
		foreach(jApp::config()->error_handling['sensitiveParameters'] as $param){
			if($param!=''&&isset($params[$param])){
				$params[$param]='***';
			}
		}
		return str_replace("\n",' ',var_export($params,true));
	}
}
interface jILogger{
	function logMessage($message);
	function output($response);
}
class jFileLogger implements jILogger{
	function logMessage($message){
		if(!is_writable(jApp::logPath()))
			return;
		$type=$message->getCategory();
		$appConf=jApp::config();
		if($appConf){
			$conf=& jApp::config()->fileLogger;
			if(!isset($conf[$type]))
				return;
			$f=$conf[$type];
			$f=str_replace('%m%',date("m"),$f);
			$f=str_replace('%Y%',date("Y"),$f);
			$f=str_replace('%d%',date("d"),$f);
			$f=str_replace('%H%',date("H"),$f);
		}
		else{
			$f='errors.log';
		}
		$coord=jApp::coord();
		if($coord&&$coord->request){
			$ip=$coord->request->getIP();
		}
		else{
			$ip=isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		}
		$f=str_replace('%ip%',$ip,$f);
		try{
			$sel=new jSelectorLog($f);
			$file=$sel->getPath();
			@error_log(date("Y-m-d H:i:s")."\t".$ip."\t$type\t".$message->getFormatedMessage()."\n",3,$file);
			@chmod($file,jApp::config()->chmodFile);
		}
		catch(Exception $e){
			$file=jApp::logPath('errors.log');
			@error_log(date("Y-m-d H:i:s")."\t".$ip."\terror\t".$e->getMessage()."\n",3,$file);
			@chmod($file,jApp::config()->chmodFile);
		}
	}
	function output($response){}
}
class jLog{
	protected static $loggers=array();
	protected static $allMessages=array();
	protected static $messagesCount=array();
	private function __construct(){}
	public static function dump($obj,$label='',$category='default'){
		$message=new jLogDumpMessage($obj,$label,$category);
		self::_dispatchLog($message);
	}
	public static function log($message,$category='default'){
		if(!is_object($message)||! $message instanceof jILogMessage)
			$message=new jLogMessage($message,$category);
		self::_dispatchLog($message);
	}
	public static function logEx($exception,$category='default'){
		$message=new jLogErrorMessage($category,
										$exception->getCode(),$exception->getMessage(),
										$exception->getFile(),$exception->getLine(),
										$exception->getTrace());
		self::_dispatchLog($message);
	}
	protected static function _dispatchLog($message){
		$confLoggers=&jApp::config()->logger;
		$category=$message->getCategory();
		if(!isset($confLoggers[$category])
			||strpos($category,'option_')===0){
			$category='default';
		}
		$all=$confLoggers['_all'];
		$loggers=preg_split('/[\s,]+/',$confLoggers[$category]);
		if($all!=''){
			$allLoggers=preg_split('/[\s,]+/',$all);
			self::_log($message,$allLoggers);
			$loggers=array_diff($loggers,$allLoggers);
		}
		self::_log($message,$loggers);
	}
	protected static function _log($message,$loggers){
		foreach($loggers as $loggername){
			if($loggername=='')
				continue;
			if($loggername=='memory'){
				$confLog=&jApp::config()->memorylogger;
				$cat=$message->getCategory();
				if(isset($confLog[$cat]))
					$max=intval($confLog[$cat]);
				else{
					$max=intval($confLog['default']);
				}
				if(!isset(self::$messagesCount[$cat])){
					self::$messagesCount[$cat]=0;
				}
				if(++self::$messagesCount[$cat] > $max){
					continue;
				}
				self::$allMessages[]=$message;
				continue;
			}
			if(!isset(self::$loggers[$loggername])){
				if($loggername=='file')
					self::$loggers[$loggername]=new jFileLogger();
				elseif($loggername=='syslog'){
					require(JELIX_LIB_CORE_PATH.'log/jSyslogLogger.class.php');
					self::$loggers[$loggername]=new jSyslogLogger();
				}
				elseif($loggername=='mail'){
					require(JELIX_LIB_CORE_PATH.'log/jMailLogger.class.php');
					self::$loggers[$loggername]=new jMailLogger();
				}
				else{
					$l=jApp::loadPlugin($loggername,'logger','.logger.php',$loggername.'Logger');
					if(is_null($l))
						continue;
					self::$loggers[$loggername]=$l;
				}
			}
			self::$loggers[$loggername]->logMessage($message);
		}
	}
	public static function getMessages($filter=false){
		if($filter===false||self::$allMessages===null)
			return self::$allMessages;
		if(is_string($filter))
			$filter=array($filter);
		$list=array();
		foreach(self::$allMessages as $msg){
			if(in_array($msg->getCategory(),$filter))
				$list[]=$msg;
		}
		return $list;
	}
	static function getMessagesCount($category){
		if(isset(self::$messagesCount[$category])){
			return self::$messagesCount[$category];
		}
		return 0;
	}
	public static function outputLog($response){
		foreach(self::$loggers as $logger){
			$logger->output($response);
		}
	}
	public static function isPluginActivated($logger,$category){
		$confLog=&jApp::config()->logger;
		$loggers=preg_split('/[\s,]+/',$confLog['_all']);
		if(in_array($logger,$loggers))
			return true;
		if(!isset($confLog[$category]))
			return false;
		$loggers=preg_split('/[\s,]+/',$confLog[$category]);
		return in_array($logger,$loggers);
	}
}
interface jISimpleCompiler{
	public function compile($aSelector);
}
interface jIMultiFileCompiler{
	public function compileItem($sourceFile,$module);
	public function endCompile($cachefile);
}
class jIncluder{
	protected static $_includedFiles=array();
	private function __construct(){}
	public static function inc($aSelector){
		$cachefile=$aSelector->getCompiledFilePath();
		if($cachefile==''||isset(jIncluder::$_includedFiles[$cachefile])){
			return;
		}
		$mustCompile=jApp::config()->compilation['force']||!file_exists($cachefile);
		if(!$mustCompile){
			$isValid=require($cachefile);
			if($isValid===true){
				jIncluder::$_includedFiles[$cachefile]=true;
				return;
			}
		}
		$sourcefile=$aSelector->getPath();
		$compiler=$aSelector->getCompiler();
		if(!$compiler||!$compiler->compile($aSelector)){
			throw new jException('jelix~errors.includer.source.compile',array($aSelector->toString(true)));
		}
		if(function_exists('opcache_invalidate')){
			opcache_invalidate($cachefile,true);
		}
		else if(function_exists('apc_delete_file')){
			apc_delete_file($cachefile);
		}
		require($cachefile);
		jIncluder::$_includedFiles[$cachefile]=true;
	}
	public static function incAll($aType,$force=false){
		$cachefile=jApp::tempPath('compiled/'.$aType[3]);
		if(isset(jIncluder::$_includedFiles[$cachefile])&&!$force){
			return;
		}
		$config=jApp::config();
		$mustCompile=$config->compilation['force']||!file_exists($cachefile);
		if(!$mustCompile&&$config->compilation['checkCacheFiletime']){
			$compiledate=filemtime($cachefile);
			foreach($config->_modulesPathList as $module=>$path){
				$sourcefile=$path.$aType[2];
				if(is_readable($sourcefile)){
					if(filemtime($sourcefile)> $compiledate){
						$mustCompile=true;
						break;
					}
				}
			}
		}
		if($mustCompile){
			require_once(JELIX_LIB_PATH.$aType[1]);
			$compiler=new $aType[0];
			$compileok=true;
			foreach($config->_modulesPathList as $module=>$path){
				$compileok=$compiler->compileItem($path.$aType[2],$module);
				if(!$compileok)break;
			}
			if($compileok){
				$compiler->endCompile($cachefile);
				if(function_exists('opcache_invalidate')){
					opcache_invalidate($cachefile,true);
				}
				else if(function_exists('apc_delete_file')){
					apc_delete_file($cachefile);
				}
				require($cachefile);
				jIncluder::$_includedFiles[$cachefile]=true;
			}
		}else{
			require($cachefile);
			jIncluder::$_includedFiles[$cachefile]=true;
		}
	}
}
class jSession{
	protected static $_params;
	public static function start(){
		$params=& jApp::config()->sessions;
		if(jApp::coord()->request instanceof jCmdLineRequest||!$params['start']){
			return false;
		}
		if(!$params['shared_session'])
			session_set_cookie_params(0,jApp::urlBasePath());
		if($params['storage']!=''){
			if(!ini_get('session.gc_probability'))
				ini_set('session.gc_probability','1');
			switch($params['storage']){
				case 'dao':
					session_set_save_handler(
						array(__CLASS__,'daoOpen'),
						array(__CLASS__,'daoClose'),
						array(__CLASS__,'daoRead'),
						array(__CLASS__,'daoWrite'),
						array(__CLASS__,'daoDestroy'),
						array(__CLASS__,'daoGarbageCollector')
					);
					self::$_params=$params;
					break;
				case 'files':
					session_save_path($params['files_path']);
					break;
			}
		}
		if($params['name']!=''){
			session_name($params['name']);
		}
		if(isset($params['_class_to_load'])){
			foreach($params['_class_to_load'] as $file){
				require_once($file);
			}
		}
		session_start();
		return true;
	}
	public static function end(){
		session_write_close();
		return true;
	}
	public static function isStarted(){
		if(php_sapi_name()!=='cli'){
			if(version_compare(phpversion(),'5.4.0','>=')){
				return(session_status()===PHP_SESSION_ACTIVE);
			}else{
				return session_id()==='' ? false : true;
			}
		}
		return false;
	}
	protected static function _getDao(){
		if(isset(self::$_params['dao_db_profile'])&&self::$_params['dao_db_profile']){
			$dao=jDao::get(self::$_params['dao_selector'],self::$_params['dao_db_profile']);
		}
		else{
			$dao=jDao::get(self::$_params['dao_selector']);
		}
		return $dao;
	}
	public static function daoOpen($save_path,$session_name){
		return true;
	}
	public static function daoClose(){
		return true;
	}
	public static function daoRead($id){
		$session=self::_getDao()->get($id);
		if(!$session){
			return '';
		}
		return $session->data;
	}
	public static function daoWrite($id,$data){
		$dao=self::_getDao();
		$session=$dao->get($id);
		if(!$session){
			$session=jDao::createRecord(self::$_params['dao_selector']);
			$session->id=$id;
			$session->data=$data;
			$now=date('Y-m-d H:i:s');
			$session->creation=$now;
			$session->access=$now;
			$dao->insert($session);
		}
		else{
			$session->data=$data;
			$session->access=date('Y-m-d H:i:s');
			$dao->update($session);
		}
		return true;
	}
	public static function daoDestroy($id){
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(),'',time()-42000,'/');
		}
		self::_getDao()->delete($id);
		return true;
	}
	public static function daoGarbageCollector($maxlifetime){
		$date=new jDateTime();
		$date->now();
		$date->sub(0,0,0,0,0,$maxlifetime);
		self::_getDao()->deleteExpired($date->toString(jDateTime::DB_DTFORMAT));
		return true;
	}
}
$GLOBALS['gLibPath']=array('Config'=>JELIX_LIB_PATH.'core/',
'Db'=>JELIX_LIB_PATH.'db/','Dao'=>JELIX_LIB_PATH.'dao/',
'Forms'=>JELIX_LIB_PATH.'forms/','Event'=>JELIX_LIB_PATH.'events/',
'Tpl'=>JELIX_LIB_PATH.'tpl/','Controller'=>JELIX_LIB_PATH.'controllers/',
'Auth'=>JELIX_LIB_PATH.'auth/','Installer'=>JELIX_LIB_PATH.'installer/',
'KV'=>JELIX_LIB_PATH.'kvdb/');
function jelix_autoload($class){
	if(strpos($class,'jelix\\')===0){
		$f=LIB_PATH.str_replace('\\',DIRECTORY_SEPARATOR,$class).'.php';
	}
	else if(preg_match('/^j(Dao|Tpl|Event|Db|Controller|Forms|Auth|Config|Installer|KV).*/i',$class,$m)){
		$f=$GLOBALS['gLibPath'][$m[1]].$class.'.class.php';
	}
	elseif(preg_match('/^cDao(?:Record)?_(.+)_Jx_(.+)_Jx_(.+)$/',$class,$m)){
		if(!isset(jApp::config()->_modulesPathList[$m[1]])){
			return;
		}
		$s=new jSelectorDao($m[1].'~'.$m[2],$m[3],false);
		if(jApp::config()->compilation['checkCacheFiletime']){
			jIncluder::inc($s);
		}else{
			$f=$s->getCompiledFilePath();
			if(!file_exists($f)){
				jIncluder::inc($s);
			}
			else
				require($f);
		}
		return;
	}else{
		$f=JELIX_LIB_UTILS_PATH.$class.'.class.php';
	}
	if(file_exists($f)){
		require($f);
	}
}
spl_autoload_register("jelix_autoload");
function checkAppOpened(){
	if(!jApp::isInit()){
		header("HTTP/1.1 500 Application not available");
		header('Content-type: text/html');
		echo "checkAppOpened: jApp is not initialized!";
		exit(1);
	}
	if(file_exists(jApp::configPath('CLOSED'))){
		$message=file_get_contents(jApp::configPath('CLOSED'));
		if(jServer::isCLI()){
			echo "Application closed.".($message?"\n$message\n":"\n");
			exit(1);
		}
		if(file_exists(jApp::appPath('install/closed.html'))){
			$file=jApp::appPath('install/closed.html');
		}
		else
			$file=JELIX_LIB_PATH.'installer/closed.html';
		header("HTTP/1.1 500 Application not available");
		header('Content-type: text/html');
		echo str_replace('%message%',$message,file_get_contents($file));
		exit(1);
	}
}
function checkAppNotInstalled(){
	if(isAppInstalled()){
		if(jServer::isCLI()){
			echo "Application is installed. The script cannot be runned.\n";
		}
		else{
			header("HTTP/1.1 500 Application not available");
			header('Content-type: text/plain');
			echo "Application is installed. The script cannot be runned.\n";
		}
		exit(1);
	}
}
function isAppInstalled(){
	return file_exists(jApp::configPath('installer.ini.php'));
}
