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
function jtpl_modifier_common_wordwrap($string,$length=80,$break="\n",$cut=false)
{
	return wordwrap($string,$length,$break,$cut);
}
