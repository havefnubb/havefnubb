<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms_widget_plugin
* @author      Claudio Bernardes
* @contributor Laurent Jouanneau, Julien Issler, Dominique Papin
* @copyright   2012 Claudio Bernardes
* @copyright   2006-2018 Laurent Jouanneau, 2008-2011 Julien Issler, 2008 Dominique Papin
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class upload_htmlFormWidget extends \jelix\forms\HtmlWidget\WidgetBase{
	protected function outputJs(){
		$ctrl=$this->ctrl;
		$jFormsJsVarName=$this->builder->getjFormsJsVarName();
		$this->parentWidget->addJs("c = new ".$jFormsJsVarName."ControlString('".$ctrl->ref."', ".$this->escJsStr($ctrl->label).");\n");
		$this->commonJs();
	}
	function outputControl(){
		$attr=$this->getControlAttributes();
		$attr['type']='file';
		$attr['value']='';
		if(property_exists($this->ctrl,'accept')&&$this->ctrl->accept!=''){
			$attr['accept']=$this->ctrl->accept;
		}
		if(property_exists($this->ctrl,'capture')&&$this->ctrl->capture){
			if(is_bool($this->ctrl->capture)){
				if($this->ctrl->capture){
					$attr['capture']='true';
				}
			}
			else{
				$attr['capture']=$this->ctrl->capture;
			}
		}
		echo '<input';
		$this->_outputAttr($attr);
		echo "/>\n";
		$this->outputJs();
	}
}
