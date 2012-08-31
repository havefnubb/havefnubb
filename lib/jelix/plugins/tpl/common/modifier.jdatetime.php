<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Laurent Jouanneau
* @contributor Emmanuel Hesry, Julien Issler, Briceg
* @copyright   2006 Laurent Jouanneau
* @copyright   2009 Emmanuel Hesry
* @copyright   2010 Julien Issler, 2011 Briceg
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_modifier_common_jdatetime($date,$format_in='db_datetime',
								$format_out='lang_date'){
	if(is_null($date))
		return '';
	$formats=array(
		'lang_date'=>jDateTime::LANG_DFORMAT,
		'lang_datetime'=>jDateTime::LANG_DTFORMAT,
		'lang_time'=>jDateTime::LANG_TFORMAT,
		'lang_short_datetime'=>jDateTime::LANG_SHORT_DTFORMAT,
		'db_date'=>jDateTime::DB_DFORMAT,
		'db_datetime'=>jDateTime::DB_DTFORMAT,
		'db_time'=>jDateTime::DB_TFORMAT,
		'iso8601'=>jDateTime::ISO8601_FORMAT,
		'timestamp'=>jDateTime::TIMESTAMP_FORMAT,
		'rfc822'=>jDateTime::RFC822_FORMAT,
		'full_lang_date'=>jDateTime::FULL_LANG_DATE
		);
	if(!isset($formats[$format_in])| !isset($formats[$format_out])){
		throw new jException("jelix~errors.tpl.tag.modifier.invalid",array('','jdatetime',''));
	}
	$dt=new jDateTime();
	$dt->setFromString($date,$formats[$format_in]);
	return $dt->toString($formats[$format_out]);
}
