<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  urls_engine
* @author      Laurent Jouanneau
* @contributor Thibault PIRONT < nuKs >
* @copyright   2005-2008 Laurent Jouanneau
* @copyright   2007 Thibault PIRONT
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jSignificantUrlsCompiler implements jISimpleCompiler{
	public function compile($aSelector){
		global $gJCoord;
		$sourceFile = $aSelector->getPath();
		$cachefile = $aSelector->getCompiledFilePath();
		$xml = simplexml_load_file( $sourceFile);
		if(!$xml){
		   return false;
		}
		$typeparam = array('string'=>'([^\/]+)','char'=>'([^\/])', 'letter'=>'(\w)',
		   'number'=>'(\d+)', 'int'=>'(\d+)', 'integer'=>'(\d+)', 'digit'=>'(\d)',
		   'date'=>'([0-2]\d{3}\-(?:0[1-9]|1[0-2])\-(?:[0-2][1-9]|3[0-1]))',
			'year'=>'([0-2]\d{3})', 'month'=>'(0[1-9]|1[0-2])', 'day'=>'([0-2][1-9]|[1-2]0|3[0-1])'
		   );
		$createUrlInfos=array();
		$createUrlContent="<?php \n";
		$defaultEntrypoints=array();
		foreach($xml->children() as $name=>$tag){
		   if(!preg_match("/^(.*)entrypoint$/", $name,$m)){
			   continue;
		   }
		   $requestType= $m[1];
		   $entryPoint = (string)$tag['name'];
		   $isDefault =(isset($tag['default']) ?(((string)$tag['default']) == 'true'):false);
		   $isHttps =(isset($tag['https']) ?(((string)$tag['https']) == 'true'):false);
		   $generatedentrypoint =$entryPoint;
		   if(isset($tag['noentrypoint']) && (string)$tag['noentrypoint'] == 'true')
				$generatedentrypoint = '';
		   $parseInfos = array($isDefault);
		   if($isDefault){
			 $createUrlInfos['@'.$requestType]=array(2,$entryPoint, $isHttps);
		   }
		   $parseContent = "<?php \n";
		   foreach($tag->url as $url){
			   $module = (string)$url['module'];
			   if(isset($url['https'])){
				   $urlhttps=(((string)$url['https']) == 'true');
			   }else{
				   $urlhttps=$isHttps;
			   }
			   if(isset($url['noentrypoint']) &&((string)$url['noentrypoint']) == 'true'){
				   $urlep='';
			   }else{
				   $urlep=$generatedentrypoint;
			   }
			   if(!$isDefault && !isset($url['action']) && !isset($url['handler'])){
				 $parseInfos[]=array($module, '', '/.*/', array(), array(), array(), false);
				 $createUrlInfos[$module.'~*@'.$requestType] = array(3,$urlep, $urlhttps);
				 continue;
			   }
			   $action = (string)$url['action'];
			   if(strpos($action, ':') === false){
				  $action = 'default:'.$action;
			   }
			   if(isset($url['actionoverride'])){
				  $actionOverride = preg_split("/[\s,]+/", (string)$url['actionoverride']);
				  foreach($actionOverride as &$each){
					 if(strpos($each, ':') === false){
						$each = 'default:'.$each;
					 }
				  }
			   }else{
				  $actionOverride = false;
			   }
			   if(isset($url['handler'])){
				  $class = (string)$url['handler'];
				  $p= strpos($class,'~');
				  if($p === false)
					$selclass = $module.'~'.$class;
				  elseif( $p == 0)
					$selclass = $module.$class;
				  else
					$selclass = $class;
				  $s= new jSelectorUrlHandler($selclass);
				  if(!isset($url['action'])){
					$action = '*';
				  }
				  $createUrlContent.="include_once('".$s->getPath()."');\n";
				  $parseInfos[]=array($module, $action, $selclass, $actionOverride);
				  $createUrlInfos[$module.'~'.$action.'@'.$requestType] = array(0,$urlep, $urlhttps, $selclass);
				  if($actionOverride){
					 foreach($actionOverride as $ao){
						$createUrlInfos[$module.'~'.$ao.'@'.$requestType] = array(0,$urlep,$urlhttps, $selclass);
					 }
				  }
				  continue;
			   }
			   $listparam=array();
			   $escapes = array();
			   if(isset($url['pathinfo'])){
				  $path = (string)$url['pathinfo'];
				  $regexppath = $path;
				  if(preg_match_all("/\:([a-zA-Z_]+)/",$path,$m, PREG_PATTERN_ORDER)){
					  $listparam=$m[1];
					  foreach($url->param as $var){
						$nom = (string) $var['name'];
						$k = array_search($nom, $listparam);
						if($k === false){
						  continue;
						}
						if(isset($var['escape'])){
							$escapes[$k] =(((string)$var['escape']) == 'true');
						}else{
							$escapes[$k] = false;
						}
						if(isset($var['type'])){
						   if(isset($typeparam[(string)$var['type']]))
							  $regexp = $typeparam[(string)$var['type']];
						   else
							  $regexp = '([^\/]+)';
						}else if(isset($var['regexp'])){
							$regexp = '('.(string)$var['regexp'].')';
						}else{
							$regexp = '([^\/]+)';
						}
						$regexppath = str_replace(':'.$nom, $regexp, $regexppath);
					  }
					  foreach($listparam as $k=>$name){
						if(isset($escapes[$k])){
						   continue;
						}
						$escapes[$k] = false;
						$regexppath = str_replace(':'.$name, '([^\/]+)', $regexppath);
					  }
				  }
			   }else{
				 $regexppath='.*';
				 $path='';
			   }
			   if(isset($url['optionalTrailingSlash']) && $url['optionalTrailingSlash'] == 'true'){
					if(substr($regexppath, -1) == '/'){
						$regexppath.='?';
					}else{
						$regexppath.='\/?';
					}
			   }
			   $liststatics = array();
			   foreach($url->static as $var){
				  $liststatics[(string)$var['name']] =(string)$var['value'];
			   }
			   $parseInfos[]=array($module, $action, '!^'.$regexppath.'$!', $listparam, $escapes, $liststatics, $actionOverride);
			   $cuisel = $module.'~'.$action.'@'.$requestType;
			   $arr = array(1,$urlep, $urlhttps, $listparam, $escapes,$path, false, $liststatics);
			   if(isset($createUrlInfos[$cuisel])){
					if($createUrlInfos[$cuisel][0] == 4){
						$createUrlInfos[$cuisel][] = $arr;
					}else{
						$createUrlInfos[$cuisel] = array( 4, $createUrlInfos[$cuisel] , $arr);
					}
			   }else{
				   $createUrlInfos[$cuisel] = $arr;
			   }
			   if($actionOverride){
				  foreach($actionOverride as $ao){
					 $cuisel = $module.'~'.$ao.'@'.$requestType;
					 $arr = array(1,$urlep, $urlhttps, $listparam, $escapes,$path, true, $liststatics);
					 if(isset($createUrlInfos[$cuisel])){
						if($createUrlInfos[$cuisel][0] == 4){
							$createUrlInfos[$cuisel][] = $arr;
						}else{
							$createUrlInfos[$cuisel] = array( 4, $createUrlInfos[$cuisel] , $arr);
						}
					 }else{
						$createUrlInfos[$cuisel] = $arr;
					 }
				  }
			   }
		   }
		   $parseContent.='$GLOBALS[\'SIGNIFICANT_PARSEURL\'][\''.rawurlencode($entryPoint).'\'] = '.var_export($parseInfos, true).";\n?>";
		   jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$aSelector->file.'.'.rawurlencode($entryPoint).'.entrypoint.php',$parseContent);
		}
		$createUrlContent .='$GLOBALS[\'SIGNIFICANT_CREATEURL\'] ='.var_export($createUrlInfos, true).";\n?>";
		jFile::write(JELIX_APP_TEMP_PATH.'compiled/urlsig/'.$aSelector->file.'.creationinfos.php',$createUrlContent);
		return true;
	}
}