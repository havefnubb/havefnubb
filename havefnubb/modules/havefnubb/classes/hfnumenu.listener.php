<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnumenuListener extends jEventListener{

   /**
   * Main Menu of the navbar
   */
   function onhfnuGetMenuContent ($event) {
	  global $HfnuConfig;
	  
	  $event->add(new hfnuMenuItem('home',
			jLocale::get('havefnubb~main.home'),
			jUrl::get('havefnubb~default:index'),
			1,
			'main'));
	  
	  $event->add(new hfnuMenuItem('community',
			jLocale::get('havefnubb~main.community'),
			jUrl::get('havefnubb~default:index'),
			2,
			'main'));
	  
	  $event->add(new hfnuMenuItem('members',
			jLocale::get('havefnubb~main.member.list'),
			jUrl::get('havefnubb~members:index'),
			3,
			'main'));
	  $event->add(new hfnuMenuItem('search',
			jLocale::get('hfnusearch~search.search'),
			jUrl::get('hfnusearch~default:index'),
			4,
			'main'));
	  if ($HfnuConfig->getValue('rules','main') != '') {
		 $event->add(new hfnuMenuItem('rules',
			   jLocale::get('havefnubb~main.rules'),
			   jUrl::get('havefnubb~default:rules'),
			   5,
			   'main'));
	  }	  
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