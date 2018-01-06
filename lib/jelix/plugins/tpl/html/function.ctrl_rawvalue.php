<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Laurent Jouanneau
* @contributor  Dominique Papin, Julien Issler
* @copyright    2007-2015 Laurent Jouanneau, 2007 Dominique Papin
* @copyright    2008 Julien Issler
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_ctrl_rawvalue($tpl,$ctrlname='',$sep=', '){
	if((!isset($tpl->_privateVars['__ctrlref'])||$tpl->_privateVars['__ctrlref']=='')&&$ctrlname==''){
		return;
	}
	if($ctrlname==''){
		$ctrl=$tpl->_privateVars['__ctrl'];
		$ctrlname=$tpl->_privateVars['__ctrlref'];
	}else{
		$ctrls=$tpl->_privateVars['__form']->getControls();
		if(!isset($ctrls[$ctrlname])){
			throw new jException('jelix~formserr.unknown.control',
				array($ctrlname,$tpl->_privateVars['__form']->getSelector(),$tpl->_templateName));
		}
		$ctrl=$ctrls[$ctrlname];
	}
	if($ctrl->type=='hidden'||$ctrl->type=='captcha'||$ctrl->type=='reset')
		return;
	$editMode=!(isset($tpl->_privateVars['__formViewMode'])&&$tpl->_privateVars['__formViewMode']);
	if($ctrl->type=='submit'&&($ctrl->standalone||$editMode))
		return;
	$tpl->_privateVars['__displayed_ctrl'][$ctrlname]=true;
	$form=$tpl->_privateVars['__form'];
	if(!$form->isActivated($ctrlname))
		return;
	$value=$tpl->_privateVars['__form']->getData($ctrlname);
	if(is_array($value)){
		$s='';
		foreach($value as $v){
			$s.=$sep.htmlspecialchars($v);
		}
		echo substr($s,strlen($sep));
	}
	else if($ctrl->type=='password'){
		echo str_repeat("*",strlen($value));
	}
	else{
		echo htmlspecialchars($value);
	}
}
