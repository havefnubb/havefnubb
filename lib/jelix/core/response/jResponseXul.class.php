<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Dominique Papin, Julien Issler
* @copyright   2005-2010 Laurent Jouanneau, 2007 Dominique Papin
* @copyright   2008 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
class jResponseXul extends jResponse{
	protected $_type='xul';
	protected $_overlays=array();
	protected $_CSSLink=array();
	protected $_JSLink=array();
	protected $_JSCode=array();
	protected $_root='window';
	public $rootAttributes=array();
	public $title='';
	public $body=null;
	public $bodyTpl='';
	public $fetchOverlays=false;
	protected $_bodyTop=array();
	protected $_bodyBottom=array();
	protected $_headSent=false;
	function __construct(){
		$this->body=new jTpl();
		parent::__construct();
	}
	public function output(){
		$this->doAfterActions();
		if($this->bodyTpl!=''){
			$this->body->meta($this->bodyTpl);
			$content=$this->body->fetch($this->bodyTpl,'xul',true,false);
		}
		else
			$content='';
		jLog::outputLog($this);
		$this->_httpHeaders['Content-Type']='application/vnd.mozilla.xul+xml;charset='.$GLOBALS['gJConfig']->charset;
		$this->sendHttpHeaders();
		$this->outputHeader();
		echo implode('',$this->_bodyTop);
		echo $content;
		echo implode('',$this->_bodyBottom);
		echo '</',$this->_root,'>';
		return true;
	}
	public function outputErrors(){
		header("HTTP/1.0 500 Internal Server Error");
		header('Content-Type: application/vnd.mozilla.xul+xml;charset='.$GLOBALS['gJConfig']->charset);
		echo '<?xml version="1.0" encoding="'.$GLOBALS['gJConfig']->charset.'" ?>'."\n";
		echo '<',$this->_root,' title="Errors" xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">';
		echo '<vbox>';
		$message=$GLOBALS['gJCoord']->getGenericErrorMessage();
		echo "<description style=\"color:#FF0000;\">".htmlspecialchars($message,ENT_NOQUOTES,$GLOBALS['gJConfig']->charset)."</description>";
		echo '</vbox></',$this->_root,'>';
	}
	function addContent($content,$beforeTpl=false){
		if($beforeTpl){
			$this->_bodyTop[]=$content;
		}else{
			$this->_bodyBottom[]=$content;
		}
	}
	function addOverlay($src){
		$this->_overlays[$src]=true;
	}
	function addJSLink($src,$params=array()){
		if(!isset($this->_JSLink[$src])){
			$this->_JSLink[$src]=$params;
		}
	}
	function addCSSLink($src,$params=array()){
		if(!isset($this->_CSSLink[$src])){
			$this->_CSSLink[$src]=$params;
		}
	}
	function addJSCode($code){
		$this->_JSCode[]=$code;
	}
	protected function outputHeader(){
		$charset=$GLOBALS['gJConfig']->charset;
		echo '<?xml version="1.0" encoding="'.$charset.'" ?>'."\n";
		foreach($this->_CSSLink as $src=>$param){
			if(is_string($param))
				echo  '<?xml-stylesheet type="text/css" href="',htmlspecialchars($src,ENT_COMPAT,$charset),'" '.$param.'?>',"\n";
			else
				echo  '<?xml-stylesheet type="text/css" href="',htmlspecialchars($src,ENT_COMPAT,$charset),'" ?>',"\n";
		}
		$this->_otherthings();
		echo '<',$this->_root;
		foreach($this->rootAttributes as $name=>$value){
			echo ' ',$name,'="',htmlspecialchars($value,ENT_COMPAT,$charset),'"';
		}
		echo "  xmlns:html=\"http://www.w3.org/1999/xhtml\"
        xmlns=\"http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul\">\n";
		foreach($this->_JSLink as $src=>$params){
			echo '<script type="application/x-javascript" src="',htmlspecialchars($src),'" />',"\n";
		}
		if(count($this->_JSCode)){
			echo '<script type="application/x-javascript">
<![CDATA[
 '.implode("\n",$this->_JSCode).'
]]>
</script>';
		}
	}
	protected function doAfterActions(){
	}
	protected function _otherthings(){
		$escape=false;
		if(preg_match('!^Mozilla/5.0 \(.* rv:(\d)\.(\d).*\) Gecko/\d+.*$!',$_SERVER["HTTP_USER_AGENT"],$m)){
			if(version_compare($m[1].'.'.$m[2],'1.9')>=0){
				$escape=true;
			}
		}
		if($this->fetchOverlays){
			$sel=new jSelectorTpl($this->bodyTpl);
			$eventresp=jEvent::notify('FetchXulOverlay',array('tpl'=>$sel->toString()));
			foreach($eventresp->getResponse()as $rep){
				if(is_array($rep)){
					$this->_overlays[jUrl::get($rep[0],$rep[1])]=true;
				}elseif(is_string($rep)){
					$this->_overlays[jUrl::get($rep)]=true;
				}
			}
		}
		foreach($this->_overlays as $src=>$ok){
			echo  '<?xul-overlay href="',($escape?htmlspecialchars($src):$src),'" ?>',"\n";
		}
		$this->rootAttributes['title']=$this->title;
	}
	public function clearHeader($what){
		$cleanable=array('CSSLink','JSLink','JSCode','overlays');
		foreach($what as $elem){
			if(in_array($elem,$cleanable)){
				$name='_'.$elem;
				$this->$name=array();
			}
		}
	}
	public function getFormatType(){return 'xul';}
}
