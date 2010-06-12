<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Olivier Demah
 * @copyright  2009
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_function_html_gravatar($tpl,$email,$params=array()){
	if(! array_key_exists('default',$params))
		$params['default']=null;
	if(!array_key_exists('size',$params))
		$params['size']=60;
	if(! array_key_exists($params['username']))
		$params['username']='';
	$gravatarUrl="http://www.gravatar.com/avatar.php?";
	$gravatarUrl.="gravatar_id=".md5(strtolower($email));
	if($params['default']!=null)
		$gravatarUrl.="&amp;default=".urlencode($params['default']);
	$gravatarUrl.="&amp;size=".$params['size'];
	echo '<img src="'.$gravatarUrl. '" class="gravatar" alt="'.htmlentities($params['username']).'"/>';
}
