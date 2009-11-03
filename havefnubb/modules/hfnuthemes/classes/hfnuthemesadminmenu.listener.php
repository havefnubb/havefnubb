<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuthemesadminmenuListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetMenuContent ($event) {
	  global $gJConfig;
	  $chemin = $gJConfig->urlengine['basePath'].'hfnu/admin/';
      if ( jAcl2::check('hfnu.admin.index'))    {
		 $event->add(new masterAdminMenuItem('hfnuthemes',jLocale::get('hfnuthemes~theme.themes'), '', 30));
		 $item = new masterAdminMenuItem('theme',
											  jLocale::get('hfnuthemes~theme.themes'),
											  jUrl::get('hfnuthemes~default:index'),
											  10,
											  'hfnuthemes');
         $item->icon = $chemin . 'images/theme.png';
         $event->add($item);		 
	  }
	  
	} 
}
?>