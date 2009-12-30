<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class supported_drivers {

	function getSupportedDrivers()
	{
		$data = array();
		if ( function_exists('mysql_connect') )
			$data['mysql'] = 'MySQL';

		if ( function_exists('pg_connect') )
			$data['pgsql'] = 'PostgreSQL';

		if ( function_exists('sqlite_connect') )
			$data['sqlite'] = 'SQLite';

		return $data;
	}

	function check() {
		$dbSupported = false;
		if ( function_exists('mysql_connect') ) {
			$dbSupported = true;
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('MySQL')),'ok') ;
		}
		else
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('MySQL')),'warning') ;

		if ( function_exists('pg_connect') ) {
			$dbSupported = true;
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('PostGresql')),'ok') ;
		}
		else
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('PostGresql')),'warning') ;

		if ( function_exists('sqlite_connect') ) {
			$dbSupported = true;
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.present',array('SQLite')),'ok') ;
		}
		else
			jMessage::add(jLocale::get('hfnuinstall~install.check.module.is.not.present',array('SQLite')),'warning') ;

		return $dbSupported;
	}
}
