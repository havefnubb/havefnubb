<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

function jtpl_function_html_nospam($tpl, $email)
{
	global $gJConfig;
	$path = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/images/users/';	
	list( $mail , $domain ) = split('@',$email);
	$string = $mail.'<img src="'.$path.'at2.gif" alt="at"/>';
	$domNospam = preg_replace('/\./','<img src="'.$path.'dot.gif" alt="dot"/>',$domain);
	$string .= $domNospam;
	echo $string;	
}