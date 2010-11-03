<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Olivier Demah
 * @contributor Steven Jehannet
 * @copyright   2009 Olivier Demah, 2010 Steven Jehannet
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_html_gravatar($tpl,$email,$params=array()){
	if(! array_key_exists('default',$params))
		$params['default']=null;
	if(!array_key_exists('size',$params))
		$params['size']=60;
	if(! array_key_exists('username',$params))
		$params['username']='';
	$gravatarUrl="http://www.gravatar.com/avatar.php?";
	$gravatarUrl.="gravatar_id=".md5(strtolower($email));
	if($params['default']!=null)
		$gravatarUrl.="&amp;default=".urlencode($params['default']);
	$gravatarUrl.="&amp;size=".$params['size'];
	echo '<img src="'.$gravatarUrl. '" class="gravatar" alt="'.htmlentities($params['username']).'"/>';
}
