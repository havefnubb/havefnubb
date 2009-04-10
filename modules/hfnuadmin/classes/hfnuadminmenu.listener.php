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
      if ( jAcl2::check('hfnu.admin.index'))    {
		  $event->add(new masterAdminMenuItem('havefnubb','HaveFnu BB!', '', 20));
	  }
	  if ( jAcl2::check('hfnu.admin.config'))    {
		  $event->add(new masterAdminMenuItem('config',
											  jLocale::get('hfnuadmin~admin.config'),
											  jUrl::get('hfnuadmin~default:config'),
											  201,
											  'havefnubb'));
	  }
	  if ( jAcl2::check('hfnu.admin.category'))    {
		  $event->add(new masterAdminMenuItem('category',
											  jLocale::get('hfnuadmin~admin.categories'),
											  jUrl::get('hfnuadmin~category:index'),
											  202,
											  'havefnubb'));
	  }
	  if ( jAcl2::check('hfnu.admin.forum'))    {		  
		  $event->add(new masterAdminMenuItem('forum',
											  jLocale::get('hfnuadmin~admin.forum'),
											  jUrl::get('hfnuadmin~forum:index'),
											  203,
											  'havefnubb'));
	  }
	  if ( jAcl2::check('hfnu.admin.post'))    {
		  $event->add(new masterAdminMenuItem('notifying',
											  jLocale::get('hfnuadmin~admin.notifying'),
											  jUrl::get('hfnuadmin~notify:index'),
											  205,
											  'havefnubb'));
	  }
	  if ( jAcl2::check('hfnu.admin.member'))    {
		  $event->add(new masterAdminMenuItem('rank',
											  jLocale::get('hfnuadmin~admin.rank'),
											  jUrl::get('hfnuadmin~ranks:index'),
											  206,
											  'havefnubb'));
		  $event->add(new masterAdminMenuItem('ban',
											  jLocale::get('hfnuadmin~admin.ban'),
											  jUrl::get('hfnuadmin~ban:index'),
											  207,
											  'havefnubb'));
	  }
      if ( jAcl2::check('hfnu.admin.cache'))    {	  
		  $event->add(new masterAdminMenuItem('cache',
											  jLocale::get('hfnuadmin~admin.cache'),
											  jUrl::get('hfnuadmin~cache:index'),
											  208,
											  'havefnubb'));		  
		}
	} 
}
?>