<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnucontactListener extends jEventListener{
    
   function onmasterAdminGetDashboardWidget ($event) {
		$box = new masterAdminDashboardWidget();
		$box->title = jLocale::get('havefnubb~main.about.title',array('HfnuContact'));
		$box->content = jZone::get('havefnubb~about',array('modulename'=>'hfnucontact'));
		$event->add($box);
   } 
}