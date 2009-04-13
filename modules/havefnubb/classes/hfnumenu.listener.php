<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnumenuListener extends jEventListener{

   /**
   *
   */
	function onhfnuGetMenuContent ($event) {
	  
		  $event->add(new hfnuMenuItem('home',
											  jLocale::get('havefnubb~main.home'),
											  jUrl::get('hfnuportal~default:index'),
											  1,
											  'main'));

		  $event->add(new hfnuMenuItem('community',
											  jLocale::get('havefnubb~main.community'),
											  jUrl::get('havefnubb~default:index'),
											  2,
											  'main'));
	  
		  $event->add(new hfnuMenuItem('member',
											  jLocale::get('havefnubb~main.member.list'),
											  jUrl::get('havefnubb~members:index'),
											  3,
											  'main'));
	  	  
		  $event->add(new hfnuMenuItem('search',
											  jLocale::get('havefnubb~main.search'),
											  jUrl::get('hfnusearch~default:index'),
											  4,
											  'main'));
		  
      if ( jAcl2::check('hfnu.admin.index'))    {	  
		  $event->add(new hfnuMenuItem('admin',
											  jLocale::get('havefnubb~main.admin.panel'),
											  jUrl::get('hfnuadmin~default:index'),
											  100,
											  'main'));		  
		}
	} 
}
?>