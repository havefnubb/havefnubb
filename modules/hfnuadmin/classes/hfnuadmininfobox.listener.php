<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadmininfoboxListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetInfoBoxContent ($event) {
      if ( jAcl2::check('hfnu.admin.index'))    {
		  $event->add(new masterAdminMenuItem('portal',
											  jLocale::get('hfnuadmin~admin.back.to.havefnubb'),
											  jUrl::get('havefnubb~default:index'),
											  100,
											  'havefnubb'));
		}
	} 
}
?>