<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author      Laurent Jouanneau
* @contributor Mickaël Fradin
* @copyright   2007-2008 Laurent Jouanneau, 2007 Mickaël Fradin
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_html_formsubmits($compiler,$begin,$param=array())
{
	if(!$begin){
		return '}} $t->_privateVars[\'__submitref\']=\'\';';
	}
	if(count($param)> 2){
		$compiler->doError2('errors.tplplugin.block.bad.argument.number','formsubmits',2);
		return '';
	}
	if(count($param)){
		if(count($param)==1){
			$content='if(is_array('.$param[0].')){
                $submits_to_display = '.$param[0].';
            }
            else {
                $t->_privateVars[\'__form\'] = '.$param[0].';
                $submits_to_display=null;
            }';
		}
		else{
			$content=' $t->_privateVars[\'__form\'] = '.$param[0].";\n";
			$content.=' $submits_to_display = '.$param[1].'; ';
		}
	}else{
		$content='$submits_to_display=null;';
	}
	$content.='
if (!isset($t->_privateVars[\'__displayed_submits\'])) {
    $t->_privateVars[\'__displayed_submits\'] = array();
}
$t->_privateVars[\'__submitref\']=\'\';
foreach($t->_privateVars[\'__form\']->getSubmits() as $ctrlref=>$ctrl){ 
    if(!$t->_privateVars[\'__form\']->isActivated($ctrlref)) continue;
    if(!isset($t->_privateVars[\'__displayed_submits\'][$ctrlref]) 
        && ( $submits_to_display===null || in_array($ctrlref, $submits_to_display))){
        $t->_privateVars[\'__submitref\'] = $ctrlref;
        $t->_privateVars[\'__submit\'] = $ctrl;
';
	return $content;
}
