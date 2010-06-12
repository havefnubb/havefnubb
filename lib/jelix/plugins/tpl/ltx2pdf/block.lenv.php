<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Aubanel MONNIER
* @copyright  2007 Aubanel MONNIER
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_ltx2pdf_lenv($compiler,$begin,$param=array())
{
	static $stack=array();
	if($begin){
		array_push($stack,$param[0]);
		return 'echo \'\\begin{'.$param[0].'}\';';
	}else
		return 'echo \'\\end{'.array_pop($stack).'}\';';
}
