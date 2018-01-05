<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  core_response
 * @author      Laurent Jouanneau
 * @contributor Julien Issler, Brice Tence
 * @copyright   2010-2012 Laurent Jouanneau
 * @copyright   2011 Julien Issler, 2011 Brice Tence
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
interface jIHTMLResponsePlugin{
	public function __construct(jResponse $c);
	public function afterAction();
	public function beforeOutput();
	public function atBottom();
	public function beforeOutputError();
}
class jResponseBasicHtml extends jResponse{
	protected $_type='html';
	protected $_charset;
	protected $_lang;
	protected $_isXhtml=true;
	public $xhtmlContentType=false;
	protected $_headBottom=array();
	protected $_bodyTop=array();
	protected $_bodyBottom=array();
	public $htmlFile='';
	protected $plugins=array();
	function __construct(){
		$this->_charset=jApp::config()->charset;
		$this->_lang=jApp::config()->locale;
		$plugins=jApp::config()->jResponseHtml['plugins'];
		if($plugins){
			$plugins=preg_split('/ *, */',$plugins);
			foreach($plugins as $name){
				if(!$name)
					continue;
				$plugin=jApp::loadPlugin($name,'htmlresponse','.htmlresponse.php',$name.'HTMLResponsePlugin',$this);
				if($plugin)
					$this->plugins[$name]=$plugin;
			}
		}
		parent::__construct();
	}
	function getPlugin($name){
		if(isset($this->plugins[$name]))
			return $this->plugins[$name];
		return null;
	}
	final public function addHeadContent($content){
		$this->_headBottom[]=$content;
	}
	function addContent($content,$before=false){
		if($before){
			$this->_bodyTop[]=$content;
		}
		else{
			$this->_bodyBottom[]=$content;
		}
	}
	protected function setContentType(){
		if($this->_isXhtml&&$this->xhtmlContentType&&strstr($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')){
			$this->_httpHeaders['Content-Type']='application/xhtml+xml;charset='.$this->_charset;
		}else{
			$this->_httpHeaders['Content-Type']='text/html;charset='.$this->_charset;
		}
	}
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		foreach($this->plugins as $name=>$plugin)
			$plugin->afterAction();
		$this->doAfterActions();
		if($this->htmlFile=='')
			throw new Exception('static page is missing');
		$this->setContentType();
		jLog::outputLog($this);
		foreach($this->plugins as $name=>$plugin)
			$plugin->beforeOutput();
		$HEADBOTTOM=implode("\n",$this->_headBottom);
		$BODYTOP=implode("\n",$this->_bodyTop);
		$BODYBOTTOM=implode("\n",$this->_bodyBottom);
		$BASEPATH=jApp::config()->urlengine['basePath'];
		ob_start();
		foreach($this->plugins as $name=>$plugin)
			$plugin->atBottom();
		$BODYBOTTOM.=ob_get_clean();
		$this->sendHttpHeaders();
		include($this->htmlFile);
		return true;
	}
	protected function doAfterActions(){
	}
	public function outputErrors(){
		if(file_exists(jApp::appPath('responses/error.en_US.php')))
			$file=jApp::appPath('responses/error.en_US.php');
		else
			$file=JELIX_LIB_CORE_PATH.'response/error.en_US.php';
		$this->_headBottom=array();
		$this->_bodyBottom=array();
		$this->_bodyTop=array();
		jLog::outputLog($this);
		foreach($this->plugins as $name=>$plugin)
			$plugin->beforeOutputError();
		$HEADBOTTOM=implode("\n",$this->_headBottom);
		$BODYTOP=implode("\n",$this->_bodyTop);
		$BODYBOTTOM=implode("\n",$this->_bodyBottom);
		$BASEPATH=jApp::config()->urlengine['basePath'];
		header("HTTP/{$this->httpVersion} 500 Internal jelix error");
		header('Content-Type: text/html;charset='.$this->_charset);
		include($file);
	}
	public function setXhtmlOutput($xhtml=true){
		$this->_isXhtml=$xhtml;
	}
	final public function isXhtml(){return $this->_isXhtml;}
}
