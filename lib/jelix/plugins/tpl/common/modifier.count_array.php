<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * Plugin to display count of an array
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author
 * @copyright  2007 laurent jouanneau
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_modifier_common_count_array($aArray)
{
	return count($aArray);
}
