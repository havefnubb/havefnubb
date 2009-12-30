<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author
 * @contributor Laurent Jouanneau (utf8 compliance)
 * @copyright  2001-2003 ispi of Lincoln, Inc., 2007 Laurent Jouanneau
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_modifier_common_count_characters($string, $include_spaces = false)
{
	if($include_spaces)
	   return(iconv_strlen($string, jTpl::getEncoding()));
	return preg_match_all("/[^\s]/",$string, $match);
}