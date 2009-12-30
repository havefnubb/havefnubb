<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Aubanel MONNIER
* @copyright  2007 Aubanel MONNIER
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_block_ltx2pdf_lcmd($compiler, $begin, $param=array())
{
	if($begin){
		$param[0];
		return 'echo \'\\'.$param[0].'{\';';
	}else
		return 'echo \'}\';';
}