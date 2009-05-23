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
    
   function onHfnuAboutModule ($event) {
		$event->add( jZone::get('hfnuadmin~about',array('modulename'=>'havefnubb')));
   }
   
   function onHfnuBoxWidget ($event) {
    
		$box = new HfnuBoxWidget();
		$box->title = '';
		$box->content = jZone::get('havefnubb~footer_menu');
		$event->add($box);
        
		$box = new HfnuBoxWidget();
		$box->title = '';
		$box->content = jZone::get('havefnubb~lastposts');
		$event->add($box);
        
		$box = new HfnuBoxWidget();
		$box->title = '';
		$box->content = jZone::get('havefnubb~stats');
		$event->add($box);    
        
   }
}