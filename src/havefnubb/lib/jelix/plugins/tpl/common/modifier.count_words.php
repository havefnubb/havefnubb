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
function jtpl_modifier_common_count_words($string)
{
	$split_array=preg_split('/\s+/',$string);
	$word_count=preg_grep('/[a-zA-Z0-9\\x80-\\xff]/',$split_array);
	return count($word_count);
}
