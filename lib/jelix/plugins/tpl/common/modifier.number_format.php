<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * jTpl plugin that wraps PHP number_format function
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author     Julien Issler
 * @contributor Mickael Fradin
 * @copyright  2008-2010 Julien Issler, 2009 Mickael Fradin
 * @link       http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @since 1.1
 */
function jtpl_modifier_common_number_format($number,$decimals=0,$dec_point=false,$thousands_sep=false){
	if($dec_point==false){
		$dec_point=jLocale::get('jelix~format.decimal_point');
	}
	if($thousands_sep===false){
		$thousands_sep=jLocale::get('jelix~format.thousands_sep');
		if(strlen($thousands_sep)> 1){
			$real_thousands_sep=$thousands_sep;
			$thousands_sep='#';
		}
	}
	$number=number_format($number,$decimals,$dec_point,$thousands_sep);
	if(isset($real_thousands_sep))
		return str_replace($thousands_sep,$real_thousands_sep,$number);
	return $number;
}
