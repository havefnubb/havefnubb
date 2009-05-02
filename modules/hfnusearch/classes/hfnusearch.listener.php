<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnusearchListener extends jEventListener{
    
   function onmasterAdminGetDashboardWidget ($event) {
		$box = new masterAdminDashboardWidget();
		$box->title = jLocale::get('havefnubb~main.about.title',array('HfnuSearch'));
		$box->content = jZone::get('havefnubb~about',array('modulename'=>'hfnusearch'));
		$event->add($box);
   } 
}