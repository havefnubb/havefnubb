<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

function jtpl_function_html_avatar($tpl, $src='', $alt='') {
	//@TODO : set the width/height in the forum configuration and set the local var from this configuration
    $ext='';
    $max_width = 80;
    $max_height = 80;

    if (file_exists(JELIX_APP_WWW_PATH.$src.'.gif')) {
        $ext = ".gif";
   
    }
    elseif (file_exists(JELIX_APP_WWW_PATH.$src.'.jpg')) {
        $ext = ".jpg";

    }
    elseif (file_exists(JELIX_APP_WWW_PATH.$src.'.jpeg')) {
        $ext = ".jpeg";
 
    }
    elseif (file_exists(JELIX_APP_WWW_PATH.$src.'.png')) {
        $ext = ".png";
 
    }    
    
    echo '<img src="'.$src.$ext.'" height="'.$max_height.'" width="'.$max_width.'" alt="'.htmlentities($alt).'"/>';
}