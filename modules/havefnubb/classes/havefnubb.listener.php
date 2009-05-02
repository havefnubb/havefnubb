<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbListener extends jEventListener{
    
   function onmasterAdminGetDashboardWidget ($event) {
		$box = new masterAdminDashboardWidget();
		$box->title = jLocale::get('havefnubb~main.about.title',array('HaveFnu BB!'));
		$box->content = jZone::get('havefnubb~about',array('modulename'=>'havefnubb'));
		$event->add($box);
   }    
}