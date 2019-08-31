<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms_widget_plugin
* @author      Laurent Jouanneau
* @copyright   2017 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class recaptcha_htmlFormWidget extends  \jelix\forms\HtmlWidget\WidgetBase{
	public function outputMetaContent($resp){
		$resp->addJSLink("https://www.google.com/recaptcha/api.js",array("async"=>"async","defer"=>"defer"));
	}
	protected function outputJs(){
		$this->parentWidget->addJs('c=null;');
	}
	function outputControl(){
		$attr=$this->getControlAttributes();
		$config=jApp::config()->recaptcha;
		unset($attr['readonly']);
		if(isset($attr['class'])){
			$attr['class'].=' g-recaptcha';
		}
		else{
			$attr['class']='g-recaptcha';
		}
		if(isset($config['sitekey'])&&$config['sitekey']!=''){
			$attr['data-sitekey']=$config['sitekey'];
		}
		else{
			jLog::log("sitekey for recaptcha is missing from the configuration","warning");
		}
		foreach(array('theme','type','size','tabindex')as $param){
			if((!isset($attr['data-'.$param])||$attr['data-'.$param]=='')&&
				isset($config[$param])&&$config[$param]!=''){
				$attr['data-'.$param]=$config[$param];
			}
		}
		echo '<div ';
		$this->_outputAttr($attr);
		echo "></div>\n";
		$this->outputJs();
	}
}
