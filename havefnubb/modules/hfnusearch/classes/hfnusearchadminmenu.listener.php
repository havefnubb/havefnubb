<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnusearchadminmenuListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetMenuContent ($event) {
      if ( jAcl2::check('hfnu.admin.index'))    {

		  $event->add(new masterAdminMenuItem('searchengine',
											  jLocale::get('hfnusearch~search.admin.search.engine'),
											  jUrl::get('hfnusearch~admin:index'),
											  400,
											  'havefnubb'));
		}
	} 
}
?>