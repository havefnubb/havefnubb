<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  responsehtml_plugin
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @copyright   2010-2012 Laurent Jouanneau
* @copyright   2011 Julien Issler
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
interface jIDebugbarPlugin{
	function getCss();
	function getJavascript();
	function show($debugbar);
}
class errorsDebugbarPlugin implements jIDebugbarPlugin{
	function getCss(){return "
#jxdb-errors li.jxdb-msg-error h5 span {background-image: url('".$this->getErrorIcon()."');}

#jxdb-errors li.jxdb-msg-warning h5 span {background-image: url('".$this->getWarningIcon()."'); }
";}
	function getJavascript(){return <<<EOS
jxdb.plugins.errors = {
    init: function() {
    }
};
EOS
;
	}
	function show($debugbarPlugin){
		$info=new debugbarItemInfo('errors','Errors');
		$messages=jLog::getMessages(array('error','warning','notice','deprecated','strict'));
		if(!jLog::isPluginActivated('memory','error')){
			array_unshift($messages,new jLogErrorMessage('warning',0,"Memory logger is not activated in jLog for errors, You cannot see them",'',0,array()));
		}
		if(!jLog::isPluginActivated('memory','warning')){
			array_unshift($messages,new jLogErrorMessage('warning',0,"Memory logger is not activated in jLog for warnings, You cannot see them",'',0,array()));
		}
		if(!jLog::isPluginActivated('memory','notice')){
			array_unshift($messages,new jLogErrorMessage('notice',0,"Memory logger is not activated in jLog for notices, You cannot see them",'',0,array()));
		}
		$c=count($messages);
		if($c==0){
			$info->label='no error';
		}
		else{
			$info->popupContent='<ul id="jxdb-errors" class="jxdb-list">';
			$maxLevel=0;
			$currentCount=array('error'=>0,'warning'=>0,'notice'=>0,'deprecated'=>0,'strict'=>0);
			foreach($messages as $msg){
				$cat=$msg->getCategory();
				$currentCount[$cat]++;
				if($msg instanceOf jLogErrorMessage){
					if($cat=='error')
						$maxLevel=1;
					$info->popupContent.='<li class="jxdb-msg-'.$cat.'">
                    <h5><a href="#" onclick="jxdb.toggleDetails(this);return false;"><span>'.htmlspecialchars($msg->getMessage()).'</span></a></h5>
                    <div><p>Code: '.$msg->getCode().'<br/> File: '.htmlspecialchars($msg->getFile()).' '.htmlspecialchars($msg->getLine()).'</p>';
					$info->popupContent.=$debugbarPlugin->formatTrace($msg->getTrace());
					$info->popupContent.='</div></li>';
				}
				else{
					$info->popupContent.='<li class="jxdb-msg-'.$cat.'">
                    <h5><a href="#" onclick="jxdb.toggleDetails(this);return false;"><span>'.htmlspecialchars($msg->getMessage()).'</span></a></h5>
                    <div><p>Not a real PHP '.$cat.',  logged directly by your code. <br />Details are not available.</p></div></li>';
				}
			}
			if($maxLevel){
				$info->htmlLabel='<img src="'.$this->getErrorIcon().'" alt="Errors" title="'.$c.' errors"/> '.$c;
				$info->popupOpened=true;
			}
			else{
				$info->htmlLabel='<img src="'.$this->getWarningIcon().'" alt="Warnings" title="There are '.$c.' warnings" /> '.$c;
			}
			$info->popupContent.='</ul>';
			foreach($currentCount as $type=>$count){
				if(($c=jLog::getMessagesCount($type))> $count){
					$info->popupContent.='<p class="jxdb-msg-warning">There are '.$c.' '.$type.' messages. Only first '.$count.' messages are shown.</p>';
				}
			}
		}
		$debugbarPlugin->addInfo($info);
	}
	protected function getErrorIcon(){
	}
	protected function getWarningIcon(){
	}
}
class debugbarItemInfo{
	public $id='';
	public $label='';
	public $htmlLabel='';
	public $popupContent='';
	public $popupOpened=false;
	function __construct($id,$label,$htmlLabel='',$popupContent='',$isOpened=false){
		$this->id=$id;
		$this->label=$label;
		$this->htmlLabel=$htmlLabel;
		$this->popupContent=$popupContent;
		$this->popupOpened=$isOpened;
	}
}
class debugbarHTMLResponsePlugin implements jIHTMLResponsePlugin{
	protected $response=null;
	protected $plugins=array();
	protected $tabs=array();
	public function __construct(jResponse $c){
		$this->response=$c;
		$this->plugins['errors']=new errorsDebugbarPlugin();
	}
	public function afterAction(){
	}
	public function beforeOutput(){
		$plugins=jApp::config()->debugbar['plugins'];
		$css="


";
		$js='';
		if($plugins){
			$plugins=preg_split('/ *, */',$plugins);
			foreach($plugins as $name){
				$plugin=jApp::loadPlugin($name,'debugbar','.debugbar.php',$name.'DebugbarPlugin',$this);
				if($plugin){
					$this->plugins[$name]=$plugin;
				}
			}
		}
		foreach($this->plugins as $name=>$plugin){
			$css.=$plugin->getCSS();
			$js.=$plugin->getJavascript();
		}
		$this->response->addHeadContent('
<style type="text/css">
#jxdb {
    position:absolute;
    right:10px;top:0px; left: auto;
    margin:0;
    padding:0px;
    z-index:1000;
    font-size:10pt;
    font-family:arial;
    font-weight:normal;
    color:black;
}
#jxdb-pjlx-a-right { display:none;}
#jxdb-pjlx-a-left { display:inline;}
#jxdb.jxdb-position-l {left:10px; right: auto;}
#jxdb.jxdb-position-l #jxdb-pjlx-a-right { display:inline;}
#jxdb.jxdb-position-l #jxdb-pjlx-a-left { display:none;}
#jxdb-header {
    padding:3px;
    background:-moz-linear-gradient(top, #EFF4F6, #87CDEF);
    background-color: #EFF4F6;    
    border-radius:0px 0px  5px 5px ;-webkit-border-bottom-right-radius: 5px;-webkit-border-bottom-left-radius: 5px;-o-border-radius:0px 0px  5px 5px ;-moz-border-radius:0px 0px  5px 5px;
    box-shadow: #6B6F80 3px 3px 6px 0px;-moz-box-shadow: #969CB4 3px 3px 6px 0px;-webkit-box-shadow: #6B6F80 3px 3px 6px;-o-box-shadow: #6B6F80 3px 3px 6px 0px;
    font-size:10pt;
    color:#797979;
    float:right;
    z-index:1200;
    position:relative;
}
#jxdb.jxdb-position-l #jxdb-header { float:left;}
#jxdb-header img {vertical-align: middle;}
#jxdb-header a img {border:0px;}
#jxdb-header span {
    display:inline-block;
    border-right: 1px solid #93B6B8;
    padding: 0 0.5em;
    color:black;
}
#jxdb-header a {text-decoration:none;color:black;}
#jxdb-header span a:hover {text-decoration:underline;}

#jxdb-tabpanels {
    clear:both;
    background-color: #CCE4ED;
    border-radius:0px 0px  5px 5px ;-moz-border-radius: 0 0 5px 5px;-o-border-radius:0px 0px  5px 5px ;-webkit-border-bottom-left-radius: 5px;-webkit-border-bottom-right-radius: 5px;
    box-shadow: #6B6F80 3px 3px 3px 0px;-moz-box-shadow: #969CB4 3px 3px 3px 0px;-webkit-box-shadow: #6B6F80 3px 3px 3px;-o-box-shadow: #6B6F80 3px 3px 3px 0px;
    z-index:1100;
    margin:0;
    padding:0;
    color:black;
    position:relative;
    max-height:700px;
    overflow: auto;
    resize:both;
}
#jxdb-tabpanels div.jxdb-tabpanel { padding:4px; }
.jxdb-list {margin:10px; padding:8px 8px 8px 8px; list-style-type:none;}
.jxdb-list li {margin:3px 0; padding:0 0 0 0px; background-color: #D0E6F4;}
.jxdb-list h5 a {color:black;text-decoration:none;display:inline-block;padding:0 0 0 18px;background-position:left center; background-repeat: no-repeat;}
.jxdb-list h5 span {display:inline-block;padding:0 0 0 18px;background-position: left center;background-repeat:no-repeat;}
.jxdb-list h5 {display:block;margin:0;padding:0;font-size:12pt;font-weight:normal; background-color:#FFF9C2;}
.jxdb-list p {margin:0 0 0 18px;font-size:10pt;}
.jxdb-list table {margin:0 0 0 18px;font-size:9pt;font-family:courier new, monospace;color:#3F3F3F; width:100%;}
#jxdb-errors li {background-color: inherit;}
#jxdb-errors li.jxdb-msg-error h5 {background-color:#FFD3D3;}
#jxdb-errors li.jxdb-msg-notice h5 {background-color:#DDFFE6;}
#jxdb-errors li.jxdb-msg-warning h5 { background-color:#FFB94E;}
.jxdb-list li >div {display:none;}
.jxdb-list li.jxdb-opened >div {display:block;}
p.jxdb-msg-error { background-color:#FFD3D3;}
p.jxdb-msg-warning { background-color:#FFB94E;}

'.$css.'
</style>
<script type="text/javascript">//<![CDATA[
var jxdb={plugins:{},init:function(event){for(var i in jxdb.plugins)jxdb.plugins[i].init();var pos=jxdb.readCookie(\'jxdebugbarpos\');if(pos)jxdb.moveTo(pos)},me:function(){return document.getElementById(\'jxdb\')},close:function(){document.getElementById(\'jxdb\').style.display="none"},selectTab:function(tabPanelId){var close=(document.getElementById(tabPanelId).style.display==\'block\');this.hideTab();if(!close){document.getElementById(\'jxdb-tabpanels\').style.display=\'block\';document.getElementById(tabPanelId).style.display=\'block\'}},hideTab:function(){var panels=document.getElementById(\'jxdb-tabpanels\').childNodes;for(var i=0;i<panels.length;i++){var elt=panels[i];if(elt.nodeType==elt.ELEMENT_NODE){elt.style.display=\'none\'}}document.getElementById(\'jxdb-tabpanels\').style.display=\'none\'},moveTo:function(side){document.getElementById(\'jxdb\').setAttribute(\'class\',\'jxdb-position-\'+side);this.createCookie(\'jxdebugbarpos\',side)},createCookie:function(name,value){var date=new Date();date.setTime(date.getTime()+(7*24*60*60*1000));document.cookie=name+"="+value+"; expires="+date.toGMTString()+"; path=/"},readCookie:function(name){var nameEQ=name+"=";var ca=document.cookie.split(\';\');for(var i=0;i<ca.length;i++){var c=ca[i].replace(/^\s\s*/,\'\').replace(/\s\s*$/,\'\');if(c.indexOf(nameEQ)==0)return c.substring(nameEQ.length,c.length)}return null},toggleDetails:function(anchor){var item=anchor.parentNode.parentNode;var cssclass=item.getAttribute(\'class\');if(cssclass==null)cssclass=\'\';if(cssclass.indexOf(\'jxdb-opened\')==-1){item.setAttribute(\'class\',cssclass+" jxdb-opened");item.childNodes[3].style.display=\'block\'}else{item.setAttribute(\'class\',cssclass.replace("jxdb-opened",\'\'));item.childNodes[3].style.display=\'none\'}}};if(window.addEventListener)window.addEventListener("load",jxdb.init,false);
'.$js.' //]]>
</script>
');
	}
	public function atBottom(){
		foreach($this->plugins as $plugin){
			$plugin->show($this);
		}
		?>
<div id="jxdb">
    <div id="jxdb-header">

<?php foreach($this->tabs as $item){
	$label=($item->htmlLabel ? $item->htmlLabel: htmlspecialchars($item->label));
	if($item->popupContent){
		echo '<span><a href="javascript:jxdb.selectTab(\'jxdb-panel-'.$item->id.'\');">'.$label.'</a></span>';
	}
	else
		echo '<span>'.$label.'</span>';
}
?>

    </div>
    <div id="jxdb-tabpanels">
        <div id="jxdb-panel-jelix" class="jxdb-tabpanel" style="display:none">
            <ul>
                <li>Jelix version: <?php echo JELIX_VERSION?></li>
                <li>Move the debug bar <a id="jxdb-pjlx-a-right" href="javascript:jxdb.moveTo('r')">to right</a>
                <a href="javascript:jxdb.moveTo('l')" id="jxdb-pjlx-a-left">to left</a></li>
            </ul>
        </div>
        <?php
		$alreadyOpen=false;
		foreach($this->tabs as $item){
			if(!$item->popupContent)
				continue;
			echo '<div id="jxdb-panel-'.$item->id.'" class="jxdb-tabpanel"';
			if($item->popupOpened&&!$alreadyOpen){
				$alreadyOpen=true;
				echo ' style="display:block"';
			}
			else
				echo ' style="display:none"';
			echo '>',$item->popupContent;
			echo '</div>';
		}?>
    </div>
</div>
        <?php
}
	public function beforeOutputError(){
		$this->beforeOutput();
		ob_start();
		$this->atBottom();
		$this->response->addContent(ob_get_clean(),true);
	}
	function addInfo($info){
		$this->tabs[]=$info;
	}
	function formatTrace($trace){
		$html='<table>';
		foreach($trace as $k=>$t){
			if(isset($t['file'])){
				$file=$t['file'];
				$path='';
				$shortcut='';
				if(strpos($file,LIB_PATH)===0){
					$path=LIB_PATH;
					$shortcut='lib:';
				}
				elseif(strpos($file,jApp::tempPath())===0){
					$path=jApp::tempPath();
					$shortcut='temp:';
				}
				elseif(strpos($file,jApp::appPath())===0){
					$path=jApp::appPath();
					$shortcut='app:';
				}
				else{
					$path=dirname(jApp::appPath());
					$shortcut='app:';
					while($path!='.'&&$path!=''){
						$shortcut.='../';
						if(strpos($file,$path)===0){
							break;
						}
						$path=dirname($path);
					}
					if($path=='.')
						$path='';
				}
				if($path!=''){
					$cut=($path[0]=='/'?0:1);
					$file='<i>'.$shortcut.'</i>'.substr($file,strlen($path)+$cut);
				}
			}
			else{
				$file='[php]';
			}
			$html.='<tr><td>'.$k.'</td><td>'.(isset($t['class'])?$t['class'].$t['type']:'').$t['function'].'()</td>';
			$html.='<td>'.($file).'</td><td>'.(isset($t['line'])?$t['line']:'').'</td></tr>';
		}
		$html.='</table>';
		return $html;
	}
}
