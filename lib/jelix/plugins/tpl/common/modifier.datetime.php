<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @copyright   2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_modifier_common_datetime($date,$format_out='lang_datetime',$format_in=''){
	if(!($date instanceof DateTime)){
		if($date=='')
			return '';
		if($format_in){
			$date=date_create_from_format($format_in,$date);
		}
		else{
			$date=new DateTime($date);
		}
	}
	$format=array(
		'lang_date'=>'jelix~format.date',
		'lang_datetime'=>'jelix~format.short_datetime',
		'lang_time'=>'jelix~format.time',
		'lang_long_datetime'=>'jelix~format.datetime',
	);
	if(isset($format[$format_out])){
		$format_out=jLocale::get($format[$format_out]);
	}
	return $date->format($format_out);
}
