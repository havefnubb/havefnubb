<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

function jtpl_function_html_jurlescape($tpl, $str, $highlevel=false,$return=false)
{

     if ($return === false)
          echo jUrl::escape($str, $highlevel);
     else
          return jUrl::escape($str, $highlevel);
     
}
