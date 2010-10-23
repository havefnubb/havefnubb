<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   jtpl_plugin
* @author       Laurent Jouanneau
* @contributor  Dominique Papin
* @copyright    2007-2008 Laurent Jouanneau, 2007 Dominique Papin
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_ctrl_control($tpl,$ctrlname='',$attributes=array())
{
	if((!isset($tpl->_privateVars['__ctrlref'])||$tpl->_privateVars['__ctrlref']=='')&&$ctrlname==''){
		return;
	}
	if($ctrlname==''){
		$ctrl=$tpl->_privateVars['__ctrl'];
		$ctrlname=$tpl->_privateVars['__ctrlref'];
	}
	else{
		$ctrls=$tpl->_privateVars['__form']->getControls();
		if(!isset($ctrls[$ctrlname])){
			throw new jException('jelix~formserr.unknown.control',
				array($ctrlname,$tpl->_privateVars['__form']->getSelector(),$tpl->_templateName));
		}
		$ctrl=$ctrls[$ctrlname];
	}
	if($ctrl->type=='submit'||$ctrl->type=='reset'||$ctrl->type=='hidden')
		return;
	$tpl->_privateVars['__displayed_ctrl'][$ctrlname]=true;
	if($tpl->_privateVars['__form']->isActivated($ctrlname)){
		$tpl->_privateVars['__formbuilder']->outputControl($ctrl,$attributes);
	}
}
