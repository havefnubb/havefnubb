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
#jxdb-errors li.jxdb-msg-notice h5 span {background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKcSURBVDjLpZPLa9RXHMU/d0ysZEwmMQqZiTaP0agoaKGJUiwIxU0hUjtUQaIuXHSVbRVc+R8ICj5WvrCldJquhVqalIbOohuZxjDVxDSP0RgzyST9zdzvvffrQkh8tBs9yy9fPhw45xhV5X1U8+Yhc3U0LcEdVxdOVq20OA0ooQjhpnfhzuDZTx6++m9edfDFlZGMtXKxI6HJnrZGGtauAWAhcgwVnnB/enkGo/25859l3wIcvpzP2EhuHNpWF9/dWs/UnKW4EOGDkqhbQyqxjsKzMgM/P1ymhlO5C4ezK4DeS/c7RdzQoa3x1PaWenJjJZwT9rQ1gSp/js1jYoZdyfX8M1/mp7uFaTR8mrt29FEMQILr62jQ1I5kA8OF59jIItVA78dJertTiBNs1ZKfLNG+MUHX1oaURtIHEAOw3p/Y197MWHEJEUGCxwfHj8MTZIcnsGKxzrIURYzPLnJgbxvG2hMrKdjItjbV11CYKeG8R7ygIdB3sBMFhkem0RAAQ3Fuka7UZtRHrasOqhYNilOwrkrwnhCU/ON5/q04vHV48ThxOCuoAbxnBQB+am65QnO8FqMxNCjBe14mpHhxBBGCWBLxD3iyWMaYMLUKsO7WYH6Stk1xCAGccmR/Ozs/bKJuXS39R/YgIjgROloSDA39Deit1SZWotsjD8pfp5ONqZ6uTfyWn+T7X0f59t5fqDhUA4ry0fYtjJcWeZQvTBu4/VqRuk9/l9Fy5cbnX+6Od26s58HjWWaflwkusKGxjm1bmhkvLXHvh1+WMbWncgPfZN+qcvex6xnUXkzvSiYP7EvTvH4toDxdqDD4+ygT+cKMMbH+3MCZ7H9uAaDnqytpVX8cDScJlRY0YIwpAjcNcuePgXP/P6Z30QuoP4J7WbYhuQAAAABJRU5ErkJggg==');}
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
			$info->htmlLabel='<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAKfSURBVDjLpZPrS1NhHMf9O3bOdmwDCWREIYKEUHsVJBI7mg3FvCxL09290jZj2EyLMnJexkgpLbPUanNOberU5taUMnHZUULMvelCtWF0sW/n7MVMEiN64AsPD8/n83uucQDi/id/DBT4Dolypw/qsz0pTMbj/WHpiDgsdSUyUmeiPt2+V7SrIM+bSss8ySGdR4abQQv6lrui6VxsRonrGCS9VEjSQ9E7CtiqdOZ4UuTqnBHO1X7YXl6Daa4yGq7vWO1D40wVDtj4kWQbn94myPGkCDPdSesczE2sCZShwl8CzcwZ6NiUs6n2nYX99T1cnKqA2EKui6+TwphA5k4yqMayopU5mANV3lNQTBdCMVUA9VQh3GuDMHiVcLCS3J4jSLhCGmKCjBEx0xlshjXYhApfMZRP5CyYD+UkG08+xt+4wLVQZA1tzxthm2tEfD3JxARH7QkbD1ZuozaggdZbxK5kAIsf5qGaKMTY2lAU/rH5HW3PLsEwUYy+YCcERmIjJpDcpzb6l7th9KtQ69fi09ePUej9l7cx2DJbD7UrG3r3afQHOyCo+V3QQzE35pvQvnAZukk5zL5qRL59jsKbPzdheXoBZc4saFhBS6AO7V4zqCpiawuptwQG+UAa7Ct3UT0hh9p9EnXT5Vh6t4C22QaUDh6HwnECOmcO7K+6kW49DKqS2DrEZCtfuI+9GrNHg4fMHVSO5kE7nAPVkAxKBxcOzsajpS4Yh4ohUPPWKTUh3PaQEptIOr6BiJjcZXCwktaAGfrRIpwblqOV3YKdhfXOIvBLeREWpnd8ynsaSJoyESFphwTtfjN6X1jRO2+FxWtCWksqBApeiFIR9K6fiTpPiigDoadqCEag5YUFKl6Yrciw0VOlhOivv/Ff8wtn0KzlebrUYwAAAABJRU5ErkJggg==" alt="no errors" title="no errors"/> 0';
		}
		else{
			$info->popupContent='<ul id="jxdb-errors" class="jxdb-list">';
			$maxLevel=0;
			$popupOpened=false;
			$currentCount=array('error'=>0,'warning'=>0,'notice'=>0,'deprecated'=>0,'strict'=>0);
			$openOnString=jApp::config()->debugbar['errors_openon'];
			$openOn=array();
			if($openOnString=='*'){
				$popupOpened=true;
			}else{
				$openOn=preg_split("/\s*,\s*/",strtoupper($openOnString));
			}
			foreach($messages as $msg){
				$cat=$msg->getCategory();
				$currentCount[$cat]++;
				if($msg instanceOf jLogErrorMessage){
					if($cat=='error')
						$maxLevel=1;
					if(!$popupOpened&&in_array(strtoupper($cat),$openOn)!==FALSE){
						$popupOpened=true;
					}
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
			}else{
				$info->htmlLabel='<img src="'.$this->getWarningIcon().'" alt="Warnings" title="There are '.$c.' warnings" /> '.$c;
			}
			$info->popupOpened=$popupOpened;
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
	return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJPSURBVDjLpZPLS5RhFMYfv9QJlelTQZwRb2OKlKuINuHGLlBEBEOLxAu46oL0F0QQFdWizUCrWnjBaDHgThCMoiKkhUONTqmjmDp2GZ0UnWbmfc/ztrC+GbM2dXbv4ZzfeQ7vefKMMfifyP89IbevNNCYdkN2kawkCZKfSPZTOGTf6Y/m1uflKlC3LvsNTWArr9BT2LAf+W73dn5jHclIBFZyfYWU3or7T4K7AJmbl/yG7EtX1BQXNTVCYgtgbAEAYHlqYHlrsTEVQWr63RZFuqsfDAcdQPrGRR/JF5nKGm9xUxMyr0YBAEXXHgIANq/3ADQobD2J9fAkNiMTMSFb9z8ambMAQER3JC1XttkYGGZXoyZEGyTHRuBuPgBTUu7VSnUAgAUAWutOV2MjZGkehgYUA6O5A0AlkAyRnotiX3MLlFKduYCqAtuGXpyH0XQmOj+TIURt51OzURTYZdBKV2UBSsOIcRp/TVTT4ewK6idECAihtUKOArWcjq/B8tQ6UkUR31+OYXP4sTOdisivrkMyHodWejlXwcC38Fvs8dY5xaIId89VlJy7ACpCNCFCuOp8+BJ6A631gANQSg1mVmOxxGQYRW2nHMha4B5WA3chsv22T5/B13AIicWZmNZ6cMchTXUe81Okzz54pLi0uQWp+TmkZqMwxsBV74Or3od4OISPr0e3SHa3PX0f3HXKofNH/UIG9pZ5PeUth+CyS2EMkEqs4fPEOBJLsyske48/+xD8oxcAYPzs4QaS7RR2kbLTTOTQieczfzfTv8QPldGvTGoF6/8AAAAASUVORK5CYII=';
	}
	protected function getWarningIcon(){
	return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIsSURBVDjLpVNLSJQBEP7+h6uu62vLVAJDW1KQTMrINQ1vPQzq1GOpa9EppGOHLh0kCEKL7JBEhVCHihAsESyJiE4FWShGRmauu7KYiv6Pma+DGoFrBQ7MzGFmPr5vmDFIYj1mr1WYfrHPovA9VVOqbC7e/1rS9ZlrAVDYHig5WB0oPtBI0TNrUiC5yhP9jeF4X8NPcWfopoY48XT39PjjXeF0vWkZqOjd7LJYrmGasHPCCJbHwhS9/F8M4s8baid764Xi0Ilfp5voorpJfn2wwx/r3l77TwZUvR+qajXVn8PnvocYfXYH6k2ioOaCpaIdf11ivDcayyiMVudsOYqFb60gARJYHG9DbqQFmSVNjaO3K2NpAeK90ZCqtgcrjkP9aUCXp0moetDFEeRXnYCKXhm+uTW0CkBFu4JlxzZkFlbASz4CQGQVBFeEwZm8geyiMuRVntzsL3oXV+YMkvjRsydC1U+lhwZsWXgHb+oWVAEzIwvzyVlk5igsi7DymmHlHsFQR50rjl+981Jy1Fw6Gu0ObTtnU+cgs28AKgDiy+Awpj5OACBAhZ/qh2HOo6i+NeA73jUAML4/qWux8mt6NjW1w599CS9xb0mSEqQBEDAtwqALUmBaG5FV3oYPnTHMjAwetlWksyByaukxQg2wQ9FlccaK/OXA3/uAEUDp3rNIDQ1ctSk6kHh1/jRFoaL4M4snEMeD73gQx4M4PsT1IZ5AfYH68tZY7zv/ApRMY9mnuVMvAAAAAElFTkSuQmCC';
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
		if($plugins){
			$plugins=preg_split('/ *, */',$plugins);
			foreach($plugins as $name){
				$plugin=jApp::loadPlugin($name,'debugbar','.debugbar.php',$name.'DebugbarPlugin',$this);
				if($plugin){
					$this->plugins[$name]=$plugin;
				}
			}
		}
	}
	public function atBottom(){
		$css="";
		$js='';
		foreach($this->plugins as $name=>$plugin){
			$css.=$plugin->getCSS();
			$js.=$plugin->getJavascript();
		}
		?>
<style type="text/css">
#jxdb {position:absolute;right:10px;top:0;left:auto;margin:0;padding:0;z-index:5000;font-size:10pt;font-family:arial;font-weight:normal;color:black;}
#jxdb-pjlx-a-right { display:none;}
#jxdb-pjlx-a-left { display:inline;}
#jxdb.jxdb-position-l {left:10px; right: auto;}
#jxdb.jxdb-position-l #jxdb-pjlx-a-right { display:inline;}
#jxdb.jxdb-position-l #jxdb-pjlx-a-left { display:none;}
#jxdb-header {
    padding:3px;font-size:10pt;color:#797979;float:right;z-index:1200;position:relative;
    background:-moz-linear-gradient(to bottom, #EFF4F6, #87CDEF);background:-webkit-linear-gradient(top, #EFF4F6, #87CDEF);background-color: #EFF4F6;background:linear-gradient(to bottom, #EFF4F6, #87CDEF);
    -webkit-border-bottom-right-radius: 5px;-webkit-border-bottom-left-radius: 5px;-o-border-radius:0 0  5px 5px ;-moz-border-radius:0 0 5px 5px;
    border-radius:0 0 5px 5px ;
    -moz-box-shadow: #969CB4 3px 3px 6px 0;-webkit-box-shadow: #6B6F80 3px 3px 6px;-o-box-shadow: #6B6F80 3px 3px 6px 0;
    box-shadow: #6B6F80 3px 3px 6px 0;
}
#jxdb.jxdb-position-l #jxdb-header { float:left;}
#jxdb-header img {vertical-align: middle;}
#jxdb-header a img {border:0;}
#jxdb-header span {display:inline-block;border-right: 1px solid #93B6B8;padding: 0 0.5em;color:black;}
#jxdb-header a {text-decoration:none;color:black;}
#jxdb-header span a:hover {text-decoration:underline;}
#jxdb-tabpanels {
    clear:both;color:black;background-color: #CCE4ED;z-index:1100;margin:0;padding:0;position:relative;max-height:700px;overflow: auto;resize:both;
    -moz-border-radius: 0 0 5px 5px;-o-border-radius:0 0 5px 5px ;-webkit-border-bottom-left-radius: 5px;-webkit-border-bottom-right-radius: 5px;border-radius:0 0  5px 5px;
    -moz-box-shadow: #969CB4 3px 3px 3px 0;-webkit-box-shadow: #6B6F80 3px 3px 3px;-o-box-shadow: #6B6F80 3px 3px 3px 0;box-shadow: #6B6F80 3px 3px 3px 0;
}
#jxdb-tabpanels div.jxdb-tabpanel { padding:4px; }
.jxdb-list {margin:10px; padding:8px 8px 8px 8px; list-style-type:none;}
.jxdb-list li {margin:3px 0; padding:0 0 0 0; background-color: #D0E6F4;}
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

ul.jxdb-list li h5 a {background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAABjSURBVCjPY/jPgB8y0FHBkb37/+/6v+X/+v8r/y/ei0XB3v+H4HDWfywKtgAl1v7/D8SH/k/ApmANUAICDv1vx6ZgMZIJ9dgUzEJyQxk2BRPWdf1vAeqt/F/yP3/dwIQk2QoAfUogHsamBmcAAAAASUVORK5CYII=');}
ul.jxdb-list li.jxdb-opened  h5 a {background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAABhSURBVCjPY/jPgB8y0FHBkb37/+/6v+X/+v8r/y/ei0XB3v+H4HDWfywKtgAl1oLhof8TsClYA5SAgEP/27EpWIxkQj02BbOQ3FCGTcGEdV3/W4B6K/+X/M9fNzAhSbYCAMiTH3pTNa+FAAAAAElFTkSuQmCC');}
<?php echo $css ?>
</style>
<script type="text/javascript">//<![CDATA[
var jxdb={plugins:{},init:function(event){for(var i in jxdb.plugins)jxdb.plugins[i].init()},me:function(){return document.getElementById('jxdb')},close:function(){document.getElementById('jxdb').style.display="none"},selectTab:function(tabPanelId){var close=(document.getElementById(tabPanelId).style.display=='block');this.hideTab();if(!close){document.getElementById('jxdb-tabpanels').style.display='block';document.getElementById(tabPanelId).style.display='block'}},hideTab:function(){var panels=document.getElementById('jxdb-tabpanels').childNodes;for(var i=0;i<panels.length;i++){var elt=panels[i];if(elt.nodeType==elt.ELEMENT_NODE){elt.style.display='none'}}document.getElementById('jxdb-tabpanels').style.display='none'},moveTo:function(side){document.getElementById('jxdb').setAttribute('class','jxdb-position-'+side);this.createCookie('jxdebugbarpos',side)},createCookie:function(name,value){var date=new Date();date.setTime(date.getTime()+(7*24*60*60*1000));document.cookie=name+"="+value+"; expires="+date.toGMTString()+"; path=/"},toggleDetails:function(anchor){var item=anchor.parentNode.parentNode;var cssclass=item.getAttribute('class');if(cssclass==null)cssclass='';if(cssclass.indexOf('jxdb-opened')==-1){item.setAttribute('class',cssclass+" jxdb-opened");item.childNodes[3].style.display='block'}else{item.setAttribute('class',cssclass.replace("jxdb-opened",''));item.childNodes[3].style.display='none'}}};if(window.addEventListener)window.addEventListener("load",jxdb.init,false);
<?php echo $js ?> //]]>
</script>
        <?php
		foreach($this->plugins as $plugin){
			$plugin->show($this);
		}
		if(isset($_COOKIE['jxdebugbarpos']))
			$class="jxdb-position-".$_COOKIE['jxdebugbarpos'];
		else
			$class="jxdb-position-".(jApp::config()->debugbar['defaultPosition']=='left'?'l':'r');
		?>
<div id="jxdb" class="<?php echo $class;?>">
    <div id="jxdb-header">
   <a href="javascript:jxdb.selectTab('jxdb-panel-jelix');"><img src="data:image/png;base64,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" alt="Jelix debug toolbar"/></a>
<?php foreach($this->tabs as $item){
	$label=($item->htmlLabel ? $item->htmlLabel: htmlspecialchars($item->label));
	if($item->popupContent){
		echo '<span><a href="javascript:jxdb.selectTab(\'jxdb-panel-'.$item->id.'\');">'.$label.'</a></span>';
	}
	else
		echo '<span>'.$label.'</span>';
}
?>
   <a href="javascript:jxdb.close();"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHdSURBVDjLpZNraxpBFIb3a0ggISmmNISWXmOboKihxpgUNGWNSpvaS6RpKL3Ry//Mh1wgf6PElaCyzq67O09nVjdVlJbSDy8Lw77PmfecMwZg/I/GDw3DCo8HCkZl/RlgGA0e3Yfv7+DbAfLrW+SXOvLTG+SHV/gPbuMZRnsyIDL/OASziMxkkKkUQTJJsLaGn8/iHz6nd+8mQv87Ahg2H9Th/BxZqxEkEgSrq/iVCvLsDK9awtvfxb2zjD2ARID+lVVlbabTgWYTv1rFL5fBUtHbbeTJCb3EQ3ovCnRC6xAgzJtOE+ztheYIEkqbFaS3vY2zuIj77AmtYYDusPy8/zuvunJkDKXM7tYWTiyGWFjAqeQnAD6+7ueNx/FLpRGAru7mcoj5ebqzszil7DggeF/DX1nBN82rzPqrzbRayIsLhJqMPT2N83Sdy2GApwFqRN7jFPL0tF+10cDd3MTZ2AjNUkGCoyO6y9cRxfQowFUbpufr1ct4ZoHg+Dg067zduTmEbq4yi/UkYidDe+kaTcP4ObJIajksPd/eyx3c+N2rvPbMDPbUFPZSLKzcGjKPrbJaDsu+dQO3msfZzeGY2TCvKGYQhdSYeeJjUt21dIcjXQ7U7Kv599f4j/oF55W4g/2e3b8AAAAASUVORK5CYII=" alt="close" title="click to close the debug toolbar"/></a>
    </div>
    <div id="jxdb-tabpanels">
        <div id="jxdb-panel-jelix" class="jxdb-tabpanel" style="display:none">
            <ul>
                <li>Jelix version: <?php echo JELIX_VERSION?></li>
                <li>Move the debug bar <a id="jxdb-pjlx-a-right" href="javascript:jxdb.moveTo('r')">to right</a>
                <a href="javascript:jxdb.moveTo('l')" id="jxdb-pjlx-a-left">to left</a></li>
                <li>To remove it definitively, deactivate the plugin "debugbar"<br/> into the configuration</li>
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
