<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Lepeltier kévin
 * @contributor Dominique Papin, Rob2
 * @copyright   2007-2008 Lepeltier kévin, 2008 Dominique Papin, 2010 Rob2
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_html_image($tpl,$src,$params=array()){
	$att=jImageModifier::get($src,$params,false);
	if(!array_key_exists('alt',$att))
		$att['alt']='';
	echo '<img';
	foreach($att as $key=>$val){
		if(!empty($val)||$key=='alt')
			echo ' '.$key.'="'.htmlspecialchars($val).'"';
	}
	echo '/>';
}
