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
* @copyright 2005-2010 Laurent Jouanneau
* @copyright 2001-2005 CopixTeam
* @copyright 2006 Mathaud Loic
* @copyright 2007-2009 Julien Issler
* @link http://www.copix.org
* @link     http://www.jelix.org
* @licence  GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
define('JELIX_VERSION','1.2pre.1543');
define('JELIX_NAMESPACE_BASE','http://jelix.org/ns/');
define('JELIX_LIB_PATH',dirname(__FILE__).'/');
define('JELIX_LIB_CORE_PATH',JELIX_LIB_PATH.'core/');
define('JELIX_LIB_UTILS_PATH',JELIX_LIB_PATH.'utils/');
define('LIB_PATH',dirname(JELIX_LIB_PATH).'/');
define('BYTECODE_CACHE_EXISTS',function_exists('apc_cache_info')||function_exists('eaccelerator_info')||function_exists('xcache_info'));
if(!defined('E_DEPRECATED'))
	define('E_DEPRECATED',8192);
if(!defined('E_USER_DEPRECATED'))
	define('E_USER_DEPRECATED',16384);
error_reporting(E_ALL | E_STRICT);
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
function jErrorHandler($errno,$errmsg,$filename,$linenum,$errcontext){
	global $gJConfig,$gJCoord;
	if(error_reporting()==0)
		return;
	$codeString=array(
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
	if(preg_match('/^\s*\((\d+)\)(.+)$/',$errmsg,$m)){
		$code=$m[1];
		$errmsg=$m[2];
	}else{
		$code=1;
	}
	$conf=$gJConfig->error_handling;
	if(isset($codeString[$errno])){
		$codestr=$codeString[$errno];
		$toDo=$conf[$codestr];
	}else{
		$codestr='error';
		$toDo=$conf['default'];
	}
	$trace=debug_backtrace();
	array_shift($trace);
	$gJCoord->handleError($toDo,$codestr,$errno,$errmsg,$filename,$linenum,$trace);
}
function jExceptionHandler($e){
	global $gJConfig,$gJCoord;
	$gJCoord->handleError($gJConfig->error_handling['exception'].' EXIT','exception',
						$e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(),$e->getTrace());
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
		if(preg_match('/^\s*\((\d+)\)(.+)$/m',$message,$m)){
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
class jContext{
	static protected $context=array();
	static function push($module){
		array_push(self::$context,$module);
	}
	static function pop(){
		return array_pop(self::$context);
	}
	static function get(){
		return end(self::$context);
	}
	static function clear(){
		self::$context=array();
	}
}
class jConfig{
	private function __construct(){}
	static public function load($configFile){
		$config=array();
		if(BYTECODE_CACHE_EXISTS)
			$file=JELIX_APP_TEMP_PATH.str_replace('/','~',$configFile).'.conf.php';
		else
			$file=JELIX_APP_TEMP_PATH.str_replace('/','~',$configFile).'.resultini.php';
		$compil=false;
		if(!file_exists($file)){
			$compil=true;
		}else{
			$t=filemtime($file);
			$dc=JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php';
			if((file_exists($dc)&&filemtime($dc)>$t)
				||filemtime(JELIX_APP_CONFIG_PATH.$configFile)>$t){
				$compil=true;
			}else{
				if(BYTECODE_CACHE_EXISTS){
					include($file);
					$config=(object) $config;
				}else{
					$config=parse_ini_file($file,true);
					$config=(object) $config;
				}
				if($config->compilation['checkCacheFiletime']){
					foreach($config->_allBasePath as $path){
						if(!file_exists($path)||filemtime($path)>$t){
							$compil=true;
							break;
						}
					}
				}
			}
		}
		if($compil){
			require_once(JELIX_LIB_CORE_PATH.'jConfigCompiler.class.php');
			return jConfigCompiler::readAndCache($configFile);
		}else
			return $config;
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
		if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_\.]+)$/",$sel,$m)){
			if($m[1]!=''&&$m[2]!=''){
				$this->module=$m[2];
			}else{
				$this->module=jContext::get();
			}
			$this->resource=$m[3];
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
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString(true));
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
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
		$this->_cachePath=JELIX_APP_TEMP_PATH.'compiled/'.$this->_dirname.$this->module.'~'.$this->resource.$this->_cacheSuffix;
	}
}
class jSelectorActFast extends jSelectorModule{
	protected $type='act';
	public $request='';
	public $controller='';
	public $method='';
	protected $_dirname='actions/';
	function __construct($request,$module,$action){
		$this->module=$module;
		$r=explode(':',$action);
		if(count($r)==1){
			$this->controller='default';
			$this->method=$r[0]==''?'index':$r[0];
		}else{
			$this->controller=$r[0]=='' ? 'default':$r[0];
			$this->method=$r[1]==''?'index':$r[1];
		}
		$this->resource=$this->controller.':'.$this->method;
		$this->request=$request;
		$this->_createPath();
	}
	protected function _createPath(){
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}else{
			$this->_path=$gJConfig->_modulesPathList[$this->module].'controllers/'.$this->controller.'.'.$this->request.'.php';
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
}
class jSelectorAct extends jSelectorActFast{
	function __construct($sel,$enableRequestPart=false){
		global $gJCoord;
		if(preg_match("/^(?:([a-zA-Z0-9_\.]+|\#)~)?([a-zA-Z0-9_:]+|\#)?(?:@([a-zA-Z0-9_]+))?$/",$sel,$m)){
			$m=array_pad($m,4,'');
			if($m[1]!=''){
				if($m[1]=='#')
					$this->module=$gJCoord->moduleName;
				else
					$this->module=$m[1];
			}else{
				$this->module=jContext::get();
			}
			if($m[2]=='#')
				$this->resource=$gJCoord->actionName;
			else
				$this->resource=$m[2];
			$r=explode(':',$this->resource);
			if(count($r)==1){
				$this->controller='default';
				$this->method=$r[0]==''?'index':$r[0];
			}else{
				$this->controller=$r[0]=='' ? 'default':$r[0];
				$this->method=$r[1]==''?'index':$r[1];
			}
			$this->resource=$this->controller.':'.$this->method;
			if($m[3]!=''&&$enableRequestPart)
				$this->request=$m[3];
			else
				$this->request=$gJCoord->request->type;
			$this->_createPath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
}
class jSelectorClass extends jSelectorModule{
	protected $type='class';
	protected $_dirname='classes/';
	protected $_suffix='.class.php';
	public $subpath='';
	public $className='';
	function __construct($sel){
		if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_\.\\/]+)$/",$sel,$m)){
			if($m[1]!=''&&$m[2]!=''){
				$this->module=$m[2];
			}else{
				$this->module=jContext::get();
			}
			$this->resource=$m[3];
			if(($p=strrpos($m[3],'/'))!==false){
				$this->className=substr($m[3],$p+1);
				$this->subpath=substr($m[3],0,$p+1);
			}else{
				$this->className=$m[3];
				$this->subpath='';
			}
			$this->_createPath();
			$this->_createCachePath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	protected function _createPath(){
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$this->subpath.$this->className.$this->_suffix;
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
			$p=jDb::getProfile($driver);
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
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$overloadedPath=JELIX_APP_VAR_PATH.'overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix;
		if(is_readable($overloadedPath)){
			$this->_path=$overloadedPath;
			$this->_where='overloaded/';
			return;
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
		if(!is_readable($this->_path)){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"dao"));
		}
		$this->_where='modules/';
	}
	protected function _createCachePath(){
		$this->_cachePath=JELIX_APP_TEMP_PATH.'compiled/daos/'.$this->_where.$this->module.'~'.$this->resource.'~'.$this->driver.$this->_cacheSuffix;
	}
	public function getDaoClass(){
		return 'cDao_'.$this->module.'_Jx_'.$this->resource.'_Jx_'.$this->driver;
	}
	public function getDaoRecordClass(){
		return 'cDaoRecord_'.$this->module.'_Jx_'.$this->resource.'_Jx_'.$this->driver;
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
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString(true));
		}
		$overloadedPath=JELIX_APP_VAR_PATH.'overloads/'.$this->module.'/'.$this->_dirname.$this->resource.$this->_suffix;
		if(is_readable($overloadedPath)){
			$this->_path=$overloadedPath;
			$this->_where='overloaded/';
			return;
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$this->resource.$this->_suffix;
		if(!is_readable($this->_path)){
			throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),$this->type));
		}
		$this->_where='modules/';
	}
	protected function _createCachePath(){
		$this->_cachePath=JELIX_APP_TEMP_PATH.'compiled/'.$this->_dirname.$this->_where.$this->module.'~'.$this->resource.$this->_cacheSuffix;
	}
	public function getCompiledBuilderFilePath($type){
		return JELIX_APP_TEMP_PATH.'compiled/'.$this->_dirname.$this->_where.$this->module.'~'.$this->resource.'_builder_'.$type.$this->_cacheSuffix;
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
		global $gJConfig;
		if($locale===null){
			$locale=$gJConfig->locale;
		}
		if($charset===null){
			$charset=$gJConfig->charset;
		}
		if(strpos($locale,'_')===false){
			$locale.='_'.strtoupper($locale);
		}
		$this->locale=$locale;
		$this->charset=$charset;
		$this->_suffix='.'.$charset.'.properties';
		$this->_compilerPath=JELIX_LIB_CORE_PATH.'jLocalesCompiler.class.php';
		if(preg_match("/^(([a-zA-Z0-9_\.]+)~)?([a-zA-Z0-9_]+)\.([a-zA-Z0-9_\.]+)$/",$sel,$m)){
			if($m[1]!=''&&$m[2]!=''){
				$this->module=$m[2];
			}else{
				$this->module=jContext::get();
			}
			$this->resource=$m[3];
			$this->fileKey=$m[3];
			$this->messageKey=$m[4];
			$this->_createPath();
			$this->_createCachePath();
		}else{
			throw new jExceptionSelector('jelix~errors.selector.invalid.syntax',array($sel,$this->type));
		}
	}
	protected function _createPath(){
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			if($this->module=='jelix')
				throw new Exception('jelix module is not enabled !!');
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$locales=array($this->locale);
		$lang=substr($this->locale,0,2);
		$generic_locale=$lang.'_'.strtoupper($lang);
		if($this->locale!==$generic_locale)
			$locales[]=$generic_locale;
		foreach($locales as $locale){
			$overloadedPath=JELIX_APP_VAR_PATH.'overloads/'.$this->module.'/locales/'.$locale.'/'.$this->resource.$this->_suffix;
			if(is_readable($overloadedPath)){
				$this->_path=$overloadedPath;
				$this->_where='overloaded/';
				$this->_cacheSuffix='.'.$locale.'.'.$this->charset.'.php';
				return;
			}
			$path=$gJConfig->_modulesPathList[$this->module].'locales/'.$locale.'/'.$this->resource.$this->_suffix;
			if(is_readable($path)){
				$this->_where='modules/';
				$this->_path=$path;
				$this->_cacheSuffix='.'.$locale.'.'.$this->charset.'.php';
				return;
			}
		}
		if($this->toString()=='jelix~errors.selector.invalid.target'){
			$l='en_EN';
			$c='UTF-8';
		}
		else{
			$l=null;
			$c=null;
		}
		throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"locale"),1,$l,$c);
	}
	protected function _createCachePath(){
		$this->_cachePath=JELIX_APP_TEMP_PATH.'compiled/locales/'.$this->_where.$this->module.'~'.$this->resource.$this->_cacheSuffix;
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
			if($GLOBALS['gJCoord']->response)
				$this->outputType=$GLOBALS['gJCoord']->response->getFormatType();
			else
				$this->outputType=$GLOBALS['gJCoord']->request->defaultResponseType;
		}else
			$this->outputType=$outputtype;
		$this->trusted=$trusted;
		$this->_compiler='jTplCompiler';
		$this->_compilerPath=JELIX_LIB_PATH.'tpl/jTplCompiler.class.php';
		parent::__construct($sel);
	}
	protected function _createPath(){
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$this->module])){
			throw new jExceptionSelector('jelix~errors.selector.module.unknown',$this->toString());
		}
		$path=$this->module.'/'.$this->resource;
		$lpath=$this->module.'/'.$gJConfig->locale.'/'.$this->resource;
		if($gJConfig->theme!='default'){
			$this->_where='themes/'.$gJConfig->theme.'/'.$lpath;
			$this->_path=JELIX_APP_VAR_PATH.$this->_where.'.tpl';
			if(is_readable($this->_path)){
				return;
			}
			$this->_where='themes/'.$gJConfig->theme.'/'.$path;
			$this->_path=JELIX_APP_VAR_PATH.$this->_where.'.tpl';
			if(is_readable($this->_path)){
				return;
			}
		}
		$this->_where='themes/default/'.$lpath;
		$this->_path=JELIX_APP_VAR_PATH.$this->_where.'.tpl';
		if(is_readable($this->_path)){
			return;
		}
		$this->_where='themes/default/'.$path;
		$this->_path=JELIX_APP_VAR_PATH.$this->_where.'.tpl';
		if(is_readable($this->_path)){
			return;
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$gJConfig->locale.'/'.$this->resource.'.tpl';
		if(is_readable($this->_path)){
			$this->_where='modules/'.$lpath;
			return;
		}
		$this->_path=$gJConfig->_modulesPathList[$this->module].$this->_dirname.$this->resource.'.tpl';
		if(is_readable($this->_path)){
			$this->_where='modules/'.$path;
			return;
		}
		throw new jExceptionSelector('jelix~errors.selector.invalid.target',array($this->toString(),"template"));
	}
	protected function _createCachePath(){
		$this->_cachePath=JELIX_APP_TEMP_PATH.'compiled/templates/'.$this->_where.'_'.$this->outputType.($this->trusted?'_t':'').$this->_cacheSuffix;
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
		$this->_basePath=JELIX_APP_VAR_PATH;
		parent::__construct($sel);
	}
}
class jSelectorCfg extends jSelectorSimpleFile{
	protected $type='cfg';
	function __construct($sel){
		$this->_basePath=JELIX_APP_CONFIG_PATH;
		parent::__construct($sel);
	}
}
class jSelectorTmp extends jSelectorSimpleFile{
	protected $type='tmp';
	function __construct($sel){
		$this->_basePath=JELIX_APP_TEMP_PATH;
		parent::__construct($sel);
	}
}
class jSelectorLog extends jSelectorSimpleFile{
	protected $type='log';
	function __construct($sel){
		$this->_basePath=JELIX_APP_LOG_PATH;
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
			$this->requestType=$GLOBALS['gJCoord']->request->type;
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
		$url='';
		if(count($this->params)>0){
			$q=http_build_query($this->params,'',($forxml?'&amp;':'&'));
			if(strpos($q,'%3A')!==false)
				$q=str_replace('%3A',':',$q);
			$url.='?'.$q;
		}
		return $url;
	}
	static function getCurrentUrl($forxml=false){
		if(isset($_SERVER["REQUEST_URI"])){
			return $_SERVER["REQUEST_URI"];
		}
		static $url=false;
		if($url===false){
			$url='http://'.$_SERVER['HTTP_HOST'].$GLOBALS['gJCoord']->request->urlScript.$GLOBALS['gJCoord']->request->urlPathInfo.'?';
			$q=http_build_query($_GET,'',($forxml?'&amp;':'&'));
			if(strpos($q,'%3A')!==false)
				$q=str_replace('%3A',':',$q);
			$url.=$q;
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
		$sel=new jSelectorAct($actSel,true);
		$params['module']=$sel->module;
		$params['action']=$sel->resource;
		$ua=new jUrlAction($params,$sel->request);
		if($what==3)return $ua;
		$url=jUrl::getEngine()->create($ua);
		if($what==2)return $url;
		return $url->toString($what!=0);
	}
	static function parse($scriptNamePath,$pathinfo,$params){
		return jUrl::getEngine()->parse($scriptNamePath,$pathinfo,$params);
	}
	static function parseFromRequest($request,$params){
		return jUrl::getEngine()->parseFromRequest($request,$params);
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
			$str=strtolower($str);
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
			global $gJConfig;
			$name=$gJConfig->urlengine['engine'];
			require_once($gJConfig->_pluginsPathList_urls[$name].$name.'.urls.php');
			$cl=$name.'UrlEngine';
			$engine=new $cl();
		}
		return $engine;
	}
}
class jCoordinator{
	public $plugins=array();
	public $response=null;
	public $request=null;
	public $action=null;
	public $moduleName;
	public $actionName;
	public $errorMessages=array();
	public $logMessages=array();
	function __construct($configFile){
		global $gJCoord,$gJConfig;
		$gJCoord=$this;
		$gJConfig=jConfig::load($configFile);
		set_error_handler('jErrorHandler');
		set_exception_handler('jExceptionHandler');
		date_default_timezone_set($gJConfig->timeZone);
		$this->_loadPlugins();
	}
	private function _loadPlugins(){
		global $gJConfig;
		foreach($gJConfig->coordplugins as $name=>$conf){
			if($conf=='1'){
				$conf=array();
			}
			else{
				$conff=JELIX_APP_CONFIG_PATH.$conf;
				if(false===($conf=parse_ini_file($conff,true)))
					die("Jelix Error: Error in the configuration file of plugin $name ($conff)!");
			}
			include($gJConfig->_pluginsPathList_coord[$name].$name.'.coord.php');
			$class=$name.'CoordPlugin';
			$this->plugins[strtolower($name)]=new $class($conf);
		}
	}
	public function process($request){
		global $gJConfig;
		$this->request=$request;
		$this->request->init();
		jSession::start();
		$this->moduleName=$request->getParam('module');
		$this->actionName=$request->getParam('action');
		if(empty($this->moduleName)){
			$this->moduleName=$gJConfig->startModule;
		}
		if(empty($this->actionName)){
			if($this->moduleName==$gJConfig->startModule)
				$this->actionName=$gJConfig->startAction;
			else{
				$this->actionName='default:index';
			}
		}
		jContext::push($this->moduleName);
		try{
			$this->action=new jSelectorActFast($this->request->type,$this->moduleName,$this->actionName);
			if($gJConfig->modules[$this->moduleName.'.access'] < 2){
				throw new jException('jelix~errors.module.untrusted',$this->moduleName);
			}
			$ctrl=$this->getController($this->action);
		}catch(jException $e){
			if($gJConfig->urlengine['notfoundAct']==''){
				throw $e;
			}
			try{
				$this->action=new jSelectorAct($gJConfig->urlengine['notfoundAct']);
				$ctrl=$this->getController($this->action);
			}catch(jException $e2){
				throw $e;
			}
		}
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
					jContext::pop();
					jContext::push($result->module);
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
		if(!$this->response->output()){
			$this->response->outputErrors();
		}
		foreach($this->plugins as $name=>$obj){
			$this->plugins[$name]->afterProcess();
		}
		jContext::pop();
		jSession::end();
	}
	private function getController($selector){
		$ctrlpath=$selector->getPath();
		require_once($ctrlpath);
		$class=$selector->getClass();
		$ctrl=new $class($this->request);
		if($ctrl instanceof jIRestController){
			$method=$selector->method=strtolower($_SERVER['REQUEST_METHOD']);
		}elseif(!method_exists($ctrl,$selector->method)){
			throw new jException('jelix~errors.ad.controller.method.unknown',array($this->actionName,$selector->method,$class,$ctrlpath));
		}
		return $ctrl;
	}
	public function initDefaultResponseOfRequest($originalResponse=false){
		if($originalResponse)
			$responses=&$GLOBALS['gJConfig']->_coreResponses;
		else
			$responses=&$GLOBALS['gJConfig']->responses;
		$type=$this->request->defaultResponseType;
		if(!isset($responses[$type]))
			return jLocale::get('jelix~errors.default.response.type.unknown',array($this->moduleName.'~'.$this->actionName,$type));
		try{
			$respclass=$responses[$type];
			require_once($responses[$type.'.path']);
			$this->response=new $respclass();
			return false;
		}
		catch(Exception $e){
			return $this->initDefaultResponseOfRequest(true);
		}
	}
	public function handleError($toDo,$type,$code,$message,$file,$line,$trace){
		global $gJConfig;
		$conf=$gJConfig->error_handling;
		$doEchoByResponse=true;
		if($this->request==null){
			$message='JELIX PANIC ! Error during initialization !! '.$message;
			$doEchoByResponse=false;
			$toDo.=' EXIT';
		}elseif($this->response==null){
			$ret=$this->initDefaultResponseOfRequest();
			if(is_string($ret)){
				$message='Double error ! 1)'. $ret.'; 2)'.$message;
				$doEchoByResponse=false;
			}
		}
		$remoteAddr=isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
		if($this->request)
			$url=str_replace('array','url',var_export($this->request->params,true));
		else $url='Unknown url';
		$messageLog=strtr($conf['messageLogFormat'],array(
			'%date%'=>date("Y-m-d H:i:s"),
			'%ip%'=>$remoteAddr,
			'%typeerror%'=>$type,
			'%code%'=>$code,
			'%msg%'=>$message,
			'%url%'=>$url,
			'%file%'=>$file,
			'%line%'=>$line,
			'\t'=>"\t",
			'\n'=>"\n"
		));
		$traceLog='';
		if(strpos($toDo,'TRACE')!==false){
			$messageLog.="\ttrace:";
			foreach($trace as $k=>$t){
				$traceLog.="\n\t$k\t".(isset($t['class'])?$t['class'].$t['type']:'').$t['function']."()\t";
				$traceLog.=(isset($t['file'])?$t['file']:'[php]').' : '.(isset($t['line'])?$t['line']:'');
			}
			$messageLog.=$traceLog."\n";
		}
		$echoAsked=false;
		if(strpos($toDo,'ECHOQUIET')!==false){
			$echoAsked=true;
			if(!$doEchoByResponse){
				header("HTTP/1.1 500 Internal jelix error");
				header('Content-type: text/plain');
				echo 'JELIX PANIC ! Error during initialization !! ';
			}elseif($this->addErrorMsg($type,$code,$conf['quietMessage'],'','','')){
				$toDo.=' EXIT';
			}
		}elseif(strpos($toDo,'ECHO')!==false){
			$echoAsked=true;
			if(!$doEchoByResponse){
				header("HTTP/1.1 500 Internal jelix error");
				header('Content-type: text/plain');
				echo $messageLog;
			}elseif($this->addErrorMsg($type,$code,$message,$file,$line,$traceLog)){
				$toDo.=' EXIT';
			}
		}
		if(strpos($toDo,'LOGFILE')!==false){
			@error_log($messageLog,3,JELIX_APP_LOG_PATH.$conf['logFile']);
		}
		if(strpos($toDo,'MAIL')!==false){
			error_log(wordwrap($messageLog,70),1,$conf['email'],$conf['emailHeaders']);
		}
		if(strpos($toDo,'SYSLOG')!==false){
			error_log($messageLog,0);
		}
		if(strpos($toDo,'EXIT')!==false){
			if($doEchoByResponse){
				if($this->response)
					$this->response->outputErrors();
				else if($echoAsked){
					header("HTTP/1.1 500 Internal jelix error");
					header('Content-type: text/plain');
					foreach($this->errorMessages as $msg)
						echo $msg."\n";
				}
			}
			jSession::end();
			exit;
		}
	}
	protected function addErrorMsg($type,$code,$message,$file,$line,$trace){
		$this->errorMessages[]=array($type,$code,$message,$file,$line,$trace);
		return !$this->response->acceptSeveralErrors();
	}
	public function addLogMsg($message,$type='default'){
		$this->logMessages[$type][]=$message;
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
	public function isModuleEnabled($moduleName){
		return isset($GLOBALS['gJConfig']->_modulesPathList[$moduleName]);
	}
	public function getModulePath($module){
		global $gJConfig;
		if(!isset($gJConfig->_modulesPathList[$module])){
			throw new Exception('getModulePath : invalid module name');
		}
		return $gJConfig->_modulesPathList[$module];
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
	public $params;
	public $type;
	public $defaultResponseType='';
	public $urlScriptPath;
	public $urlScriptName;
	public $urlScript;
	public $urlPathInfo;
	function __construct(){}
	public function init(){
		$this->_initUrlData();
		$this->_initParams();
	}
	abstract protected function _initParams();
	protected function _initUrlData(){
		global $gJConfig;
		$this->urlScript=$gJConfig->urlengine['urlScript'];
		$this->urlScriptPath=$gJConfig->urlengine['urlScriptPath'];
		$this->urlScriptName=$gJConfig->urlengine['urlScriptName'];
		$piiqp=$gJConfig->urlengine['pathInfoInQueryParameter'];
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
		if($gJConfig->isWindows&&$pathinfo&&strpos($pathinfo,$this->urlScript)!==false){
			$pathinfo=substr($pathinfo,strlen($this->urlScript));
		}
		$this->urlPathInfo=$pathinfo;
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
	public function isAllowedResponse($respclass){
		return true;
	}
	public function getResponse($type='',$useOriginal=false){
		global $gJCoord,$gJConfig;
		if($type==''){
			$type=$this->defaultResponseType;
		}
		if($useOriginal){
			if(!isset($gJConfig->_coreResponses[$type])){
				throw new jException('jelix~errors.ad.response.type.unknown',array($gJCoord->action->resource,$type,$gJCoord->action->getPath()));
			}
			$respclass=$gJConfig->_coreResponses[$type];
			$path=$gJConfig->_coreResponses[$type.'.path'];
		}else{
			if(!isset($gJConfig->responses[$type])){
				throw new jException('jelix~errors.ad.response.type.unknown',array($gJCoord->action->resource,$type,$gJCoord->action->getPath()));
			}
			$respclass=$gJConfig->responses[$type];
			$path=$gJConfig->responses[$type.'.path'];
		}
		if(!$this->isAllowedResponse($respclass)){
			throw new jException('jelix~errors.ad.response.type.notallowed',array($gJCoord->action->resource,$type,$gJCoord->action->getPath()));
		}
		if(!class_exists($respclass,false))
			require($path);
		$response=new $respclass();
		$gJCoord->response=$response;
		return $response;
	}
	function getIP(){
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])&&$_SERVER['HTTP_X_FORWARDED_FOR']){
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else if(isset($_SERVER['HTTP_CLIENT_IP'])&&$_SERVER['HTTP_CLIENT_IP']){
			return  $_SERVER['HTTP_CLIENT_IP'];
		}else{
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	function getProtocol(){
	static $proto=null;
	if($proto===null)
		$proto=(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']&&$_SERVER['HTTPS']!='off' ? 'https://':'http://');
	return $proto;
	}
	public function readHttpBody(){
	$input=file_get_contents("php://input");
	$values=array();
	if(strpos($_SERVER["CONTENT_TYPE"],"application/x-www-url-encoded")==0){
		parse_str($input,$values);
		return $values;
	}
	else if(strpos($_SERVER["CONTENT_TYPE"],"multipart/form-data")==0){
		if(!preg_match("/boundary=([a-zA-Z0-9]+)/",$_SERVER["CONTENT_TYPE"],$m))
			return $input;
		$parts=explode('--'.$m[1],$input);
		foreach($parts as $part){
			if(trim($part)==''||$part=='--')
				continue;
			list($header,$value)=explode("\r\n\r\n",$part);
			if(preg_match('/content\-disposition\:(?: *)form\-data\;(?: *)name="([^"]+)"(\;(?: *)filename="([^"]+)")?/i',$header,$m)){
				if(isset($m[2])&&$m[3]!='')
				$return[$m[1]]=array($m[3],$value);
				else
				$return[$m[1]]=$value;
			}
		}
		if(count($values))
			return $values;
		else
			return $input;
	}
	else{
		return $input;
	}
	}
}
abstract class jResponse{
	protected  $_type=null;
	protected $_acceptSeveralErrors=true;
	protected $_httpHeaders=array();
	protected $_httpHeadersSent=false;
	protected $_httpStatusCode='200';
	protected $_httpStatusMsg='OK';
	function __construct(){
	}
	abstract public function output();
	abstract public function outputErrors();
	public final function getType(){return $this->_type;}
	public function getFormatType(){return $this->_type;}
	public final function acceptSeveralErrors(){return $this->_acceptSeveralErrors;}
	public final function hasErrors(){return count($GLOBALS['gJCoord']->errorMessages)>0;}
	public function addHttpHeader($htype,$hcontent,$overwrite=true){
		if(!$overwrite&&isset($this->_httpHeaders[$htype]))
			return;
		$this->_httpHeaders[$htype]=$hcontent;
	}
	public function clearHttpHeaders(){
		$this->_httpHeaders=array();
		$this->_httpStatusCode='200';
		$this->_httpStatusMsg='OK';
	}
	public function setHttpStatus($code,$msg){$this->_httpStatusCode=$code;$this->_httpStatusMsg=$msg;}
	protected function sendHttpHeaders(){
		header("HTTP/1.1 ".$this->_httpStatusCode.' '.$this->_httpStatusMsg);
		foreach($this->_httpHeaders as $ht=>$hc){
			header($ht.': '.$hc);
		}
		$this->_httpHeadersSent=true;
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
			$charset=$GLOBALS['gJConfig']->charset;
		}
		if(!in_array($charset,$this->_loadedCharset)){
			$this->_loadLocales($this->locale,$charset);
		}
		if(isset($this->_strings[$charset][$key])){
			return $this->_strings[$charset][$key];
		}else{
			return null;
		}
	}
	protected function _loadLocales($locale,$charset){
		global $gJConfig;
		$this->_loadedCharset[]=$charset;
		$source=$this->fic->getPath();
		$cache=$this->fic->getCompiledFilePath();
		if(is_readable($cache)){
			$okcompile=true;
			if($gJConfig->compilation['force']){
				$okcompile=false;
			}else{
				if($gJConfig->compilation['checkCacheFiletime']){
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
								$this->_strings[$charset][$key].=' '.trim(str_replace(array('\#','\n'),array('#',"\n"),$sp[0]));
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
							$value=trim(str_replace('\#','#',$sp[0]));
							if($value=='\w'){
								$value=' ';
							}
						}else{
							$value='';
						}
						$this->_strings[$charset][$key]=str_replace(array('\#','\n'),array('#',"\n"),$value);
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
		$s=$GLOBALS['gJConfig']->locale;
		return substr($s,0,strpos($s,'_'));
	}
	static function getCurrentCountry(){
		$s=$GLOBALS['gJConfig']->locale;
		return substr($s,strpos($s,'_')+1);
	}
	static function get($key,$args=null,$locale=null,$charset=null){
		global $gJConfig;
		try{
			$file=new jSelectorLoc($key,$locale,$charset);
		}
		catch(jExceptionSelector $e){
			if($e->getCode()==12)throw $e;
			if($locale===null)$locale=$gJConfig->locale;
			if($charset===null)$charset=$gJConfig->charset;
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
			if($locale==$gJConfig->locale){
				throw new Exception('(210)The given locale key "'.$file->toString().'" does not exists in the default lang for the '.$file->charset.' charset');
			}
			return jLocale::get($key,$args,$gJConfig->locale);
		}
		else{
			if($args!==null&&$args!==array()){
				$string=call_user_func_array('sprintf',array_merge(array($string),is_array($args)? $args : array($args)));
			}
			return $string;
		}
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
		global $gJConfig,$gJCoord;
		$cachefile=$aSelector->getCompiledFilePath();
		if($cachefile==''||isset(jIncluder::$_includedFiles[$cachefile])){
			return;
		}
		$mustCompile=$gJConfig->compilation['force']||!file_exists($cachefile);
		if(!$mustCompile&&$gJConfig->compilation['checkCacheFiletime']){
			if(filemtime($aSelector->getPath())> filemtime($cachefile)){
				$mustCompile=true;
			}
		}
		if($mustCompile){
			$compiler=$aSelector->getCompiler();
			if($compiler&&$compiler->compile($aSelector)){
				require($cachefile);
				jIncluder::$_includedFiles[$cachefile]=true;
			}
		}else{
			require($cachefile);
			jIncluder::$_includedFiles[$cachefile]=true;
		}
	}
	public static function incAll($aType){
		global $gJConfig,$gJCoord;
		$cachefile=JELIX_APP_TEMP_PATH.'compiled/'.$aType[3];
		if(isset(jIncluder::$_includedFiles[$cachefile])){
			return;
		}
		$mustCompile=$gJConfig->compilation['force']||!file_exists($cachefile);
		if(!$mustCompile&&$gJConfig->compilation['checkCacheFiletime']){
			$compiledate=filemtime($cachefile);
			foreach($gJConfig->_modulesPathList as $module=>$path){
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
			foreach($gJConfig->_modulesPathList as $module=>$path){
				$compileok=$compiler->compileItem($path.$aType[2],$module);
				if(!$compileok)break;
			}
			if($compileok){
				$compiler->endCompile($cachefile);
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
		$params=& $GLOBALS['gJConfig']->sessions;
		if($GLOBALS['gJCoord']->request instanceof jCmdLineRequest||!$params['start']){
			return false;
		}
		if(!$params['shared_session'])
			session_set_cookie_params(0,$GLOBALS['gJConfig']->urlengine['basePath']);
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
$gJCoord=null;
$gJConfig=null;
$gLibPath=array('Db'=>JELIX_LIB_PATH.'db/','Dao'=>JELIX_LIB_PATH.'dao/',
'Forms'=>JELIX_LIB_PATH.'forms/','Event'=>JELIX_LIB_PATH.'events/',
'Tpl'=>JELIX_LIB_PATH.'tpl/','Acl'=>JELIX_LIB_PATH.'acl/','Controller'=>JELIX_LIB_PATH.'controllers/',
'Auth'=>JELIX_LIB_PATH.'auth/','Installer'=>JELIX_LIB_PATH.'installer/',
'KV'=>JELIX_LIB_PATH.'kvdb/');
function jelix_autoload($class){
	if(preg_match('/^j(Dao|Tpl|Acl|Event|Db|Controller|Forms|Auth|Installer|KV).*/i',$class,$m)){
		$f=$GLOBALS['gLibPath'][$m[1]].$class.'.class.php';
	}elseif(preg_match('/^cDao(?:Record)?_(.+)_Jx_(.+)_Jx_(.+)$/',$class,$m)){
		$s=new jSelectorDao($m[1].'~'.$m[2],$m[3],false);
		if($GLOBALS['gJConfig']->compilation['checkCacheFiletime']){
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
	if(file_exists(JELIX_APP_CONFIG_PATH.'CLOSED')){
		$message=file_get_contents(JELIX_APP_CONFIG_PATH.'CLOSED');
		if(php_sapi_name()=='cli'){
			echo "Application closed.".($message?"\n$message\n":"\n");
			exit(1);
		}
		if(file_exists(JELIX_APP_PATH.'install/closed.html')){
			$file=JELIX_APP_PATH.'install/closed.html';
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
		if(php_sapi_name()=='cli'){
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
	return file_exists(JELIX_APP_CONFIG_PATH.'installer.ini.php');
}
