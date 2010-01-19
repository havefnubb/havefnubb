<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminListener extends jEventListener{

	public function onHfnuAboutModule($event) {

		$event->Add(array(
					'moduleInfos'=>
					jClasses::getService('havefnubb~modulexml')->parse('hfnuadmin'))
					);
	}
}
