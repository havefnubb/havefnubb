<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that display menu item to manage the Contact
 */
class hfnuadmincontactmenuListener extends jEventListener{
	/**
	* the menu item
	* @param object $event
	* @return void
	*/
	 function onmasteradminGetMenuContent ($event) {
		 $chemin = jApp::urlBasePath().'hfnu/admin/';

		 if ( jAcl2::check('hfnu.admin.contact'))    {
			 $event->add(new masterAdminMenuItem('hfnucontact','Contact', '', 40));

			 $item = new masterAdminMenuItem('contact',
											 jLocale::get('hfnucontact~contact.contact'),
											 jUrl::get('hfnucontact~admin:index'),
											 100,
											 'hfnucontact');
			 $item->icon = $chemin . 'images/contact.png';
			 $event->add($item);
		 }

	 }
}
