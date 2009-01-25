<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminmenuListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetMenuContent ($event) {
	  $event->add(new masterAdminMenuItem('havefnubb','HaveFnu BB!', '', 20));
	  
	  $event->add(new masterAdminMenuItem('canfig',
										  jLocale::get('hfnuadmin~admin.index'),
										  jUrl::get('hfnuadmin~default:index'),
										  200,
										  'havefnubb'));
	  $event->add(new masterAdminMenuItem('config',
										  jLocale::get('hfnuadmin~admin.config'),
										  jUrl::get('hfnuadmin~default:config'),
										  201,
										  'havefnubb'));
	  $event->add(new masterAdminMenuItem('category',
										  jLocale::get('hfnuadmin~admin.categories'),
										  jUrl::get('hfnuadmin~default:categories'),
										  202,
										  'havefnubb'));
	  $event->add(new masterAdminMenuItem('forum',
										  jLocale::get('hfnuadmin~admin.forum'),
										  jUrl::get('hfnuadmin~default:forums'),
										  203,
										  'havefnubb'));
	  $event->add(new masterAdminMenuItem('reporting',
										  jLocale::get('hfnuadmin~admin.reporting'),
										  jUrl::get('hfnuadmin~default:reporting'),
										  205,
										  'havefnubb'));	
	  $event->add(new masterAdminMenuItem('rank',
										  jLocale::get('hfnuadmin~admin.rank'),
										  jUrl::get('hfnuadmin~default:ranks'),
										  206,
										  'havefnubb'));
	  $event->add(new masterAdminMenuItem('ban',
										  jLocale::get('hfnuadmin~admin.ban'),
										  jUrl::get('hfnuadmin~default:ban'),
										  207,
										  'havefnubb'));		
		
	} 
}
?>