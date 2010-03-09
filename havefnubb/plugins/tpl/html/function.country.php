<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * function that displays the country name from a given country code
 */
function jtpl_function_html_country($tpl,$code)
{
	echo jClasses::getService('havefnubb~country')->getCountryName($code);
}
