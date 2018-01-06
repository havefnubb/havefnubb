<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Yann, Dominique Papin
* @contributor Warren Seine, Alexis Métaireau, Julien Issler, Olivier Demah, Brice Tence
* @copyright   2005-2012 Laurent Jouanneau, 2006 Yann, 2007 Dominique Papin
* @copyright   2008 Warren Seine, Alexis Métaireau
* @copyright   2009 Julien Issler, Olivier Demah
* @copyright   2010 Brice Tence
*              few lines of code are copyrighted CopixTeam http://www.copix.org
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(__DIR__.'/jResponseBasicHtml.class.php');
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
class jResponseHtml extends jResponseBasicHtml{
	protected $_type='html';
	public $title='';
	public $favicon='';
	public $body=null;
	public $bodyTpl='';
	public $bodyErrorTpl='';
	public $bodyTagAttributes=array();
	protected $_CSSLink=array();
	protected $_CSSIELink=array();
	protected $_Styles=array();
	protected $_JSLink=array();
	protected $_JSIELink=array();
	protected $_JSCodeBefore=array();
	protected $_JSCode=array();
	protected $_MetaKeywords=array();
	protected $_MetaDescription=array();
	protected $_MetaAuthor='';
	protected $_MetaGenerator='';
	protected $_Link=array();
	protected $_endTag="/>\n";
	function __construct(){
		$this->body=new jTpl();
		parent::__construct();
	}
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		foreach($this->plugins as $name=>$plugin)
			$plugin->afterAction();
		$this->doAfterActions();
		$this->setContentType();
		if($this->bodyTpl!=''){
			$this->body->meta($this->bodyTpl);
			$content=$this->body->fetch($this->bodyTpl,'html',true,false);
		}
		else $content='';
		jLog::outputLog($this);
		foreach($this->plugins as $name=>$plugin)
			$plugin->beforeOutput();
		$this->sendHttpHeaders();
		$this->outputDoctype();
		$this->outputHtmlHeader();
		echo '<body ';
		foreach($this->bodyTagAttributes as $attr=>$value){
			echo $attr,'="',htmlspecialchars($value),'" ';
		}
		echo ">\n";
		echo implode("\n",$this->_bodyTop);
		echo $content;
		echo implode("\n",$this->_bodyBottom);
		foreach($this->plugins as $name=>$plugin)
			$plugin->atBottom();
		echo '</body></html>';
		return true;
	}
	public function setTitle($title){
		$this->title=$title;
	}
	public function addLink($href,$rel,$type='',$title=''){
		$this->_Link[$href]=array($rel,$type,$title);
	}
	public function addJSLink($src,$params=array(),$forIE=false){
		if($forIE){
			if(!isset($this->_JSIELink[$src])){
				if(!is_bool($forIE)&&!empty($forIE))
					$params['_ieCondition']=$forIE;
				$this->_JSIELink[$src]=$params;
			}
		}else{
			if(!isset($this->_JSLink[$src])){
				$this->_JSLink[$src]=$params;
			}
		}
	}
	public function addJSLinkModule($module,$src,$params=array(),$forIE=false){
		$src=jUrl::get('jelix~www:getfile',array('targetmodule'=>$module,'file'=>$src));
		if($forIE){
			if(!isset($this->_JSIELink[$src])){
				if(!is_bool($forIE)&&!empty($forIE))
					$params['_ieCondition']=$forIE;
				$this->_JSIELink[$src]=$params;
			}
		}else{
			if(!isset($this->_JSLink[$src])){
				$this->_JSLink[$src]=$params;
			}
		}
	}
	public function getJSLinks(){return $this->_JSLink;}
	public function setJSLinks($list){$this->_JSLink=$list;}
	public function getJSIELinks(){return $this->_JSIELink;}
	public function setJSIELinks($list){$this->_JSIELink=$list;}
	public function getCSSLinks(){return $this->_CSSLink;}
	public function setCSSLinks($list){$this->_CSSLink=$list;}
	public function getCSSIELinks(){return $this->_CSSIELink;}
	public function setCSSIELinks($list){$this->_CSSIELink=$list;}
	public function addCSSLink($src,$params=array(),$forIE=false){
		if($forIE){
			if(!isset($this->_CSSIELink[$src])){
				if(!is_bool($forIE)&&!empty($forIE))
					$params['_ieCondition']=$forIE;
				$this->_CSSIELink[$src]=$params;
			}
		}else{
			if(!isset($this->_CSSLink[$src])){
				$this->_CSSLink[$src]=$params;
			}
		}
	}
	public function addCSSLinkModule($module,$src,$params=array(),$forIE=false){
		$src=jUrl::get('jelix~www:getfile',array('targetmodule'=>$module,'file'=>$src));
		if($forIE){
			if(!isset($this->_CSSIELink[$src])){
				if(!is_bool($forIE)&&!empty($forIE))
					$params['_ieCondition']=$forIE;
				$this->_CSSIELink[$src]=$params;
			}
		}else{
			if(!isset($this->_CSSLink[$src])){
				$this->_CSSLink[$src]=$params;
			}
		}
	}
	public function addCSSThemeLinkModule($module,$src,$params=array(),$forIE=false){
		$src=$url=jUrl::get('jelix~www:getfile',array('targetmodule'=>$module,'file'=>'themes/'.jApp::config()->theme.'/'.$src));
		if($forIE){
			if(!isset($this->_CSSIELink[$src])){
				if(!is_bool($forIE)&&!empty($forIE))
					$params['_ieCondition']=$forIE;
				$this->_CSSIELink[$src]=$params;
			}
		}else{
			if(!isset($this->_CSSLink[$src])){
				$this->_CSSLink[$src]=$params;
			}
		}
	}
	public function addStyle($selector,$def=null){
		if(!isset($this->_Styles[$selector])){
			$this->_Styles[$selector]=$def;
		}
	}
	public function setBodyAttributes($attrArray){
		if(is_array($attrArray)){
			foreach($attrArray as $attr=>$value){
				if(!is_numeric($attr)){
					$this->bodyTagAttributes[$attr]=$value;
				}
			}
		}
	}
	public function addJSCode($code,$before=false){
		if($before)
			$this->_JSCodeBefore[]=$code;
		else
			$this->_JSCode[]=$code;
	}
	public function addMetaKeywords($content){
		$this->_MetaKeywords[]=$content;
	}
	public function addMetaDescription($content){
		$this->_MetaDescription[]=$content;
	}
	public function addMetaAuthor($content){
		$this->_MetaAuthor=$content;
	}
	public function addMetaGenerator($content){
		$this->_MetaGenerator=$content;
	}
	protected function outputDoctype(){
		echo '<!DOCTYPE HTML>',"\n";
		$lang=str_replace('_','-',$this->_lang);
		if($this->_isXhtml){
			echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="',$lang,'" lang="',$lang,'">
';
		}else{
			echo '<html lang="',$lang,'">';
		}
	}
	protected function outputJsScriptTag($fileUrl,$scriptParams){
		$params='';
		foreach($scriptParams as $param_name=>$param_value){
			if($param_name=='_ieCondition')
				continue;
			$params.=$param_name.'="'. htmlspecialchars($param_value).'" ';
		}
		echo '<script type="text/javascript" src="',htmlspecialchars($fileUrl),'" ',$params,'></script>',"\n";
	}
	protected function outputCssLinkTag($fileUrl,$cssParams){
		$params='';
		foreach($cssParams as $param_name=>$param_value){
			if($param_name=='_ieCondition')
				continue;
			$params.=$param_name.'="'. htmlspecialchars($param_value).'" ';
		}
		if(!isset($cssParams['rel']))
			$params.='rel="stylesheet" ';
		echo '<link type="text/css" href="',htmlspecialchars($fileUrl),'" ',$params,$this->_endTag,"\n";
	}
	protected function outputHtmlHeader(){
		echo '<head>'."\n";
		echo implode("\n",$this->_headTop);
		if($this->_isXhtml&&$this->xhtmlContentType&&strstr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')){
			echo '<meta content="application/xhtml+xml; charset='.$this->_charset.'" http-equiv="content-type"'.$this->_endTag;
		}else{
			echo '<meta content="text/html; charset='.$this->_charset.'" http-equiv="content-type"'.$this->_endTag;
		}
		echo '<title>'.htmlspecialchars($this->title)."</title>\n";
		if(!empty($this->_MetaDescription)){
			$description=implode(' ',$this->_MetaDescription);
			echo '<meta name="description" content="'.htmlspecialchars($description).'" '.$this->_endTag;
		}
		if(!empty($this->_MetaKeywords)){
			$keywords=implode(',',$this->_MetaKeywords);
			echo '<meta name="keywords" content="'.htmlspecialchars($keywords).'" '.$this->_endTag;
		}
		if(!empty($this->_MetaGenerator)){
			echo '<meta name="generator" content="'.htmlspecialchars($this->_MetaGenerator).'" '.$this->_endTag;
		}
		if(!empty($this->_MetaAuthor)){
			echo '<meta name="author" content="'.htmlspecialchars($this->_MetaAuthor).'" '.$this->_endTag;
		}
		foreach($this->_CSSLink as $src=>$params){
			$this->outputCssLinkTag($src,$params);
		}
		foreach($this->_CSSIELink as $src=>$params){
			if(!isset($params['_ieCondition']))
			$params['_ieCondition']='IE';
			echo '<!--[if '.$params['_ieCondition'].' ]>';
			$this->outputCssLinkTag($src,$params);
			echo '<![endif]-->';
		}
		if($this->favicon!=''){
			$fav=htmlspecialchars($this->favicon);
			echo '<link rel="icon" type="image/x-icon" href="',$fav,'" ',$this->_endTag;
			echo '<link rel="shortcut icon" type="image/x-icon" href="',$fav,'" ',$this->_endTag;
		}
		foreach($this->_Link as $href=>$params){
			$more=array();
			if(!empty($params[1]))
				$more[]='type="'.$params[1].'"';
			if(!empty($params[2]))
				$more[]='title = "'.htmlspecialchars($params[2]).'"';
			echo '<link rel="',$params[0],'" href="',htmlspecialchars($href),'" ',implode($more,' '),$this->_endTag;
		}
		if(count($this->_JSCodeBefore)){
			echo '<script type="text/javascript">
// <![CDATA[
 '.implode("\n",$this->_JSCodeBefore).'
// ]]>
</script>';
		}
		foreach($this->_JSLink as $src=>$params){
			$this->outputJsScriptTag($src,$params);
		}
		foreach($this->_JSIELink as $src=>$params){
			if(!isset($params['_ieCondition']))
				$params['_ieCondition']='IE';
			echo '<!--[if '.$params['_ieCondition'].' ]>';
			$this->outputJsScriptTag($src,$params);
			echo '<![endif]-->';
		}
		if(count($this->_Styles)){
			echo "<style type=\"text/css\">\n";
			foreach($this->_Styles as $selector=>$value){
				if(strlen($value)){
					echo $selector.' {'.$value."}\n";
				}else{
					echo $selector,"\n";
				}
			}
			echo "\n </style>\n";
		}
		if(count($this->_JSCode)){
			echo '<script type="text/javascript">
// <![CDATA[
 '.implode("\n",$this->_JSCode).'
// ]]>
</script>';
		}
		echo implode("\n",$this->_headBottom),'</head>';
	}
	public function clearHtmlHeader($what=null){
		$cleanable=array('CSSLink','CSSIELink','Styles','JSLink','JSIELink','JSCode','Others','MetaKeywords','MetaDescription');
		if($what==null)
			$what=$cleanable;
		foreach($what as $elem){
			if(in_array($elem,$cleanable)){
				$name='_'.$elem;
				$this->$name=array();
			}
		}
	}
	public function setXhtmlOutput($xhtml=true){
		$this->_isXhtml=$xhtml;
		if($xhtml)
			$this->_endTag="/>\n";
		else
			$this->_endTag=">\n";
	}
	public function endTag(){return $this->_endTag;}
}
