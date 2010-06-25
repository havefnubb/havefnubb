<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that manages the jEvent reponses
 */
class hfnusearchListener extends jEventListener{
	/**
	 * Method that returns the detail about its module
	 */
	function onHfnuAboutModule ($event) {
		$event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnusearch')) );
	}
}
