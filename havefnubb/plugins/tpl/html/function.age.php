<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

function jtpl_function_html_age($tpl, $date)
{
    $year_in_secondes = (365 * 24 * 60 * 60);
	
	echo floor( (time() - strtotime($date) ) / $year_in_secondes);
}