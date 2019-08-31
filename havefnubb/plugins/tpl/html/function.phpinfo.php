<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/** plugin that displays the info about all your webserver + php + database versions
 *    /!\ to be used in your backend  !
 */
function jtpl_function_html_phpinfo($tpl)
{
    phpinfo();
}
