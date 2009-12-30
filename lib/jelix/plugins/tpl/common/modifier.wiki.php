<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 *
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author     Laurent Jouanneau
 * @copyright  2006 Laurent Jouanneau
 * @link http://wikirenderer.berlios.de/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
require_once(JELIX_LIB_UTILS_PATH.'jWiki.class.php');
function jtpl_modifier_common_wiki($text, $config = 'wr3_to_xhtml')
{
	$wr = new jWiki($config);
	return $wr->render($text);
}