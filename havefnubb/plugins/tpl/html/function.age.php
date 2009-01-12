<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

function jtpl_function_html_age($tpl, $date)
{
    $year_in_secondes = (365 * 24 * 60 * 60);
    $today = time();
    
    list($d_y,$d_m,$d_d) = split("[/.-]",$date);
    $birth_time = mktime(0,0,0,$d_m,$d_d,$d_y); 
    $age = floor ( ($today - $birth_time) / $year_in_secondes );
    
    echo $age;
}