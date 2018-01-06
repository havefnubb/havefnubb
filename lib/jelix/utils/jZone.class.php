<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Laurent Raufaste, Pulsation
* @copyright  2001-2005 CopixTeam, 2005-2012 Laurent Jouanneau, 2008 Laurent Raufaste, 2008 Pulsation
*
* This class was get originally from the Copix project (CopixZone, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jZone{
	protected $_useCache=false;
	protected $_cacheTimeout=0;
	protected $_params;
	protected $_tplname='';
	protected $_tplOutputType='';
	protected $_tpl=null;
	protected $_cancelCache=false;
	function __construct($params=array()){
		$this->_params=$params;
	}
	public static function get($name,$params=array()){
		return self::_callZone($name,'getContent',$params);
	}
	public static function clear($name,$params=array()){
		self::_callZone($name,'clearCache',$params);
	}
	public static function clearAll($name=''){
		$dir=jApp::tempPath('zonecache/');
		if(!file_exists($dir))return;
		if($name!=''){
			$sel=new jSelectorZone($name);
			$fic='~'.$sel->module.'~'.strtolower($sel->resource).'zone~';
		}else{
			$fic='~';
		}
		if($dh=opendir($dir)){
			while(($file=readdir($dh))!==false){
				if(strpos($file,$fic)===0){
					unlink($dir.$file);
				}
			}
			closedir($dh);
		}
	}
	public function param($paramName,$defaultValue=null){
		return array_key_exists($paramName,$this->_params)? $this->_params[$paramName] : $defaultValue;
	}
	public function getContent(){
		if($this->_useCache&&!jApp::config()->zones['disableCache']){
			$cacheFiles=$this->_getCacheFiles();
			$f=$cacheFiles['content'];
			if(file_exists($f)){
				if($this->_cacheTimeout > 0){
					clearstatcache(false,$f);
					if(time()- filemtime($f)> $this->_cacheTimeout){
						unlink($f);
						$this->_cancelCache=false;
						$response=jApp::coord()->response;
						$sniffer=new jMethodSniffer($response,'$resp',array('getType','getFormatType'),true);
						jApp::coord()->response=$sniffer;
						$content=$this->_createContent();
						jApp::coord()->response=$response;
						if(!$this->_cancelCache){
							jFile::write($f,$content);
							jFile::write($cacheFiles['meta'],'<?'."php\n".(string)$sniffer);
						}
						return $content;
					}
				}
				if(file_exists($cacheFiles['meta'])){
					if(filesize($cacheFiles['meta'])> 0){
						$this->_execMetaFunc(jApp::coord()->response,$cacheFiles['meta']);
					}
				}else{
					$response=jApp::coord()->response;
					$sniffer=new jMethodSniffer($response,'$resp',array('getType','getFormatType'),true);
					jApp::coord()->response=$sniffer;
					$this->_createContent();
					jApp::coord()->response=$response;
					if(!$this->_cancelCache){
						jFile::write($cacheFiles['meta'],'<?'."php\n".(string)$sniffer);
					}
				}
				$content=file_get_contents($f);
			}else{
				$this->_cancelCache=false;
				$response=jApp::coord()->response;
				$sniffer=new jMethodSniffer($response,'$resp',array('getType','getFormatType'),true);
				jApp::coord()->response=$sniffer;
				$content=$this->_createContent();
				jApp::coord()->response=$response;
				if(!$this->_cancelCache){
					jFile::write($f,$content);
					jFile::write($cacheFiles['meta'],'<?'."php\n".(string)$sniffer);
				}
			}
		}else{
			$content=$this->_createContent();
		}
		return $content;
	}
	protected function _execMetaFunc($resp,$_file){
		include($_file);
	}
	public function clearCache(){
		if($this->_useCache){
			foreach($this->_getCacheFiles(false)as $f){
				if(file_exists($f)){
					unlink($f);
				}
			}
		}
	}
	protected function _createContent(){
		$this->_tpl=new jTpl();
		$this->_tpl->assign($this->_params);
		$this->_prepareTpl();
		if($this->_tplname=='')return '';
		return $this->_tpl->fetch($this->_tplname,$this->_tplOutputType);
	}
	protected function _prepareTpl(){
	}
	private function _getCacheFiles($forCurrentResponse=true){
		$module=jApp::getCurrentModule();
		$ar=$this->_params;
		ksort($ar);
		$id=md5(serialize($ar));
		$cacheFiles=array('content'=>jApp::tempPath('zonecache/~'.$module.'~'.strtolower(get_class($this)).'~'.$id.'.php'));
		if($forCurrentResponse){
			$respType=jApp::coord()->response->getType();
			$cacheFiles['meta']=jApp::tempPath('zonecache/~'.$module.'~'.strtolower(get_class($this)).'~meta~'.$respType.'~'.$id.'.php');
		}else{
			foreach(jApp::config()->responses as $respType){
				if(substr($respType,-5)!='.path'){
					$cacheFiles['meta.'.$respType]=jApp::tempPath('zonecache/~'.$module.'~'.strtolower(get_class($this)).'~meta~'.$respType.'~'.$id.'.php');
				}
			}
		}
		return $cacheFiles;
	}
	private static function  _callZone($name,$method,&$params){
		$sel=new jSelectorZone($name);
		jApp::pushCurrentModule($sel->module);
		$fileName=$sel->getPath();
		require_once($fileName);
		$className=$sel->resource.'Zone';
		$zone=new $className($params);
		$toReturn=$zone->$method();
		jApp::popCurrentModule();
		return $toReturn;
	}
}
