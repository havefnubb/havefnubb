<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuinstallListener extends jEventListener{
    
   function onmasterAdminGetDashboardWidget ($event) {
		$box = new masterAdminDashboardWidget();
		$box->title = jLocale::get('havefnubb~main.about.title',array('HfnuInstall'));
		$box->content = jZone::get('havefnubb~about',array('modulename'=>'hfnuinstall'));
		$event->add($box);
   }  
}