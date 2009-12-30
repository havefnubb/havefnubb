<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class servinfoListener extends jEventListener{

	function onHfnuAboutModule ($event) {
		$event->add( jZone::get('hfnuadmin~about',array('modulename'=>'servinfo')) );
	}
}
