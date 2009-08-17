<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// plugin that display the info about all your webserver + php + database versions
//    /!\ to be used in your backend  !
function jtpl_function_html_phpinfo($tpl)
{
    phpinfo();
}