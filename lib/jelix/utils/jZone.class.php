<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Laurent Raufaste, Pulsation
* @copyright  2001-2005 CopixTeam, 2005-2009 Laurent Jouanneau, 2008 Laurent Raufaste, 2008 Pulsation
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
	protected $_tplOuputType='';
	protected $_tpl=null;
	protected $_cancelCache=false;
	function __construct($params=array()){
		$this->_params=$params;
	}
	public static function get($name,$params=array()){
		return self::_callZone($name,'getContent',$params);
	}
	public static function clear($name,$params=array()){
		return self::_callZone($name,'clearCache',$params);
	}
	public static function clearAll($name=''){
		$dir=JELIX_APP_TEMP_PATH.'zonecache/';
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
	public function getParam($paramName,$defaultValue=null){
		return $this->param($paramName,$defaultValue);
	}
	public function getContent(){
		global $gJConfig;
		if($this->_useCache&&!$gJConfig->zones['disableCache']){
			$f=$this->_getCacheFile();
			if(file_exists($f)){
				if($this->_cacheTimeout > 0){
					clearstatcache();
					if(time()- filemtime($f)> $this->_cacheTimeout){
						unlink($f);
						$this->_cancelCache=false;
						$content=$this->_createContent();
						if(!$this->_cancelCache){
							jFile::write($f,$content);
						}
						return $content;
					}
				}
				if($this->_tplname!=''){
					$this->_tpl=new jTpl();
					$this->_tpl->assign($this->_params);
					$this->_tpl->meta($this->_tplname,$this->_tplOuputType);
				}
				$content=file_get_contents($f);
			}else{
				$this->_cancelCache=false;
				$content=$this->_createContent();
				if(!$this->_cancelCache){
					jFile::write($f,$content);
				}
			}
		}else{
			$content=$this->_createContent();
		}
		return $content;
	}
	public function clearCache(){
		if($this->_useCache){
			$f=$this->_getCacheFile();
			if(file_exists($f)){
				unlink($f);
			}
		}
	}
	protected function _createContent(){
		$this->_tpl=new jTpl();
		$this->_tpl->assign($this->_params);
		$this->_prepareTpl();
		if($this->_tplname=='')return '';
		return $this->_tpl->fetch($this->_tplname,$this->_tplOuputType);
	}
	protected function _prepareTpl(){
	}
	private function _getCacheFile(){
		$module=jContext::get();
		$ar=$this->_params;
		ksort($ar);
		$id=md5(serialize($ar));
		return JELIX_APP_TEMP_PATH.'zonecache/~'.$module.'~'.strtolower(get_class($this)).'~'.$id.'.php';
	}
	private static function  _callZone($name,$method,&$params){
		$sel=new jSelectorZone($name);
		jContext::push($sel->module);
		$fileName=$sel->getPath();
		require_once($fileName);
		$className=$sel->resource.'Zone';
		$zone=new $className($params);
		$toReturn=$zone->$method();
		jContext::pop();
		return $toReturn;
	}
}
