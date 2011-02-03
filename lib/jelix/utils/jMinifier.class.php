<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage core
 * @author     Brice Tence
 * @copyright  2010 Brice Tence
 *   Idea of this class was picked from the Minify project ( Minify 2.1.3, http://code.google.com/p/minify )
 * @link       http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
define('MINIFY_MIN_DIR',LIB_PATH.'minify/min/');
set_include_path(MINIFY_MIN_DIR.'/lib' . PATH_SEPARATOR . get_include_path());
require_once "Minify/Controller/MinApp.php";
require_once 'Minify/Source.php';
require_once 'Minify.php';
class jMinifier{
	protected static $_controller=null;
	protected static $_options=null;
	protected static $min_cacheFileLocking=true;
	private function __construct(){
	}
	public static function minify($fileList,$fileType){
		global $gJConfig,$gJCoord;
		$cachePathCSS='cache/minify/css/';
		jFile::createDir(JELIX_APP_WWW_PATH.$cachePathCSS);
		$cachePathJS='cache/minify/js/';
		jFile::createDir(JELIX_APP_WWW_PATH.$cachePathJS);
		$minifiedFiles=array();
		$minAppMaxFiles=count($fileList);
		$sourcesHash=md5(implode(';',$fileList));
		$options=array();
		$options['MinApp']['maxFiles']=$minAppMaxFiles;
		$cachePath='';
		$cacheExt='';
		switch($fileType){
		case 'js':
			$options['contentType']=Minify::TYPE_JS;
			$cachePath=$cachePathJS;
			$cacheExt='js';
			break;
		case 'css':
			$options['contentType']=Minify::TYPE_CSS;
			$cachePath=$cachePathCSS;
			$cacheExt='css';
			break;
		default:
			return;
		}
		$cacheFilepath=$cachePath . $sourcesHash . '.' . $cacheExt;
		$cacheFilepathFilemtime=null;
		if(is_file(JELIX_APP_WWW_PATH.$cacheFilepath)){
			$cacheFilepathFilemtime=filemtime(JELIX_APP_WWW_PATH.$cacheFilepath);
		}
		if(isset($GLOBALS['gJConfig']->responseHtml)&&
			$GLOBALS['gJConfig']->responseHtml['minifyCheckCacheFiletime']===false&&
			$cacheFilepathFilemtime!==null){
				$minifiedFiles[]=$cacheFilepath;
				return $minifiedFiles;
			}
		$sources=array();
		foreach($fileList as $file){
			$minifySource=new Minify_Source(array(
				'filepath'=>realpath($_SERVER['DOCUMENT_ROOT'] . $file)
			));
			$sources[]=$minifySource;
		}
		$controller=new Minify_Controller_MinApp();
		$controller->sources=$sources;
		$options=$controller->analyzeSources($options);
		$options=$controller->mixInDefaultOptions($options);
		self::$_options=$options;
		self::$_controller=$controller;
		if($cacheFilepathFilemtime===null||
			$cacheFilepathFilemtime < self::$_options['lastModifiedTime']){
				if(self::$_options['contentType']===Minify::TYPE_CSS&&self::$_options['rewriteCssUris']){
					reset($controller->sources);
					while(list($key,$source)=each(self::$_controller->sources)){
						if($source->filepath
							&&!isset($source->minifyOptions['currentDir'])
							&&!isset($source->minifyOptions['prependRelativePath'])
						){
							$source->minifyOptions['currentDir']=dirname($source->filepath);
						}
					}
				}
				$cacheData=self::combineAndMinify();
				$flag=self::$min_cacheFileLocking
					? LOCK_EX
					: null;
				if(is_file(JELIX_APP_WWW_PATH.$cacheFilepath)){
					@unlink(JELIX_APP_WWW_PATH.$cacheFilepath);
				}
				if(! @file_put_contents(JELIX_APP_WWW_PATH.$cacheFilepath,$cacheData,$flag)){
					return false;
				}
			}
		$minifiedFiles[]=$cacheFilepath;
		return $minifiedFiles;
	}
	protected static function combineAndMinify()
	{
		$type=self::$_options['contentType'];
		$implodeSeparator=($type===Minify::TYPE_JS)
			? "\n;"
			: '';
		$defaultOptions=isset(self::$_options['minifierOptions'][$type])
			? self::$_options['minifierOptions'][$type]
			: array();
		$defaultMinifier=isset(self::$_options['minifiers'][$type])
			? self::$_options['minifiers'][$type]
			: false;
		if(Minify_Source::haveNoMinifyPrefs(self::$_controller->sources)){
			foreach(self::$_controller->sources as $source){
				$pieces[]=$source->getContent();
			}
			$content=implode($implodeSeparator,$pieces);
			if($defaultMinifier){
				self::$_controller->loadMinifier($defaultMinifier);
				$content=call_user_func($defaultMinifier,$content,$defaultOptions);
			}
		}else{
			foreach(self::$_controller->sources as $source){
				$minifier=(null!==$source->minifier)
					? $source->minifier
					: $defaultMinifier;
				$options=(null!==$source->minifyOptions)
					? array_merge($defaultOptions,$source->minifyOptions)
					: $defaultOptions;
				if($minifier){
					self::$_controller->loadMinifier($minifier);
					$pieces[]=call_user_func($minifier,$source->getContent(),$options);
				}else{
					$pieces[]=$source->getContent();
				}
			}
			$content=implode($implodeSeparator,$pieces);
		}
		if($type===Minify::TYPE_CSS&&false!==strpos($content,'@import')){
			$content=self::_handleCssImports($content);
		}
		if(self::$_options['postprocessorRequire']){
			require_once self::$_options['postprocessorRequire'];
		}
		if(self::$_options['postprocessor']){
			$content=call_user_func(self::$_options['postprocessor'],$content,$type);
		}
		return $content;
	}
	protected static function _handleCssImports($css)
	{
		if(self::$_options['bubbleCssImports']){
			preg_match_all('/@import.*?;/',$css,$imports);
			$css=implode('',$imports[0]). preg_replace('/@import.*?;/','',$css);
		}else if(''!==Minify::$importWarning){
			$noCommentCss=preg_replace('@/\\*[\\s\\S]*?\\*/@','',$css);
			$lastImportPos=strrpos($noCommentCss,'@import');
			$firstBlockPos=strpos($noCommentCss,'{');
			if(false!==$lastImportPos
				&&false!==$firstBlockPos
				&&$firstBlockPos < $lastImportPos
			){
				$css=Minify::$importWarning . $css;
			}
		}
		return $css;
	}
}
