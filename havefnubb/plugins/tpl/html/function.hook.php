<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * hook plugin :
 *
 * @param jTpl $tpl template engine
 * @param string $event the event name to call
 * @param array $params parameters to give to the listener
 */
function jtpl_function_html_hook($tpl, $event, $params=array()) {

	if ($event == '') return;

	$events = jEvent::notify($event,$params)->getResponse();

	foreach ($events as $event)
		echo $event;

}
