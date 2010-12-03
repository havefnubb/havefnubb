<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @copyright  2001-2003 ispi of Lincoln, Inc.
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_modifier_common_regex_replace($string,$search,$replace)
{
	if(preg_match('!\W(\w+)$!s',$search,$match)&&
			(strpos($match[1],'e')!==false)){
		$search=substr($search,0,-iconv_strlen($match[1],jTpl::getEncoding())).
			str_replace('e','',$match[1]);
	}
	return preg_replace($search,$replace,$string);
}
