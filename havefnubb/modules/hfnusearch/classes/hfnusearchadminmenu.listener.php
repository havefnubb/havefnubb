<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnusearchadminmenuListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetMenuContent ($event) {
	  global $gJConfig;
	  $chemin = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
	  $event->add(new masterAdminMenuItem('hfnusearch',jLocale::get('hfnusearch~search.admin.search.engine'), '', 100));
      if ( jAcl2::check('hfnu.admin.search'))    {
		 $item = new masterAdminMenuItem('hfnusearch',
											  jLocale::get('hfnusearch~search.admin.search.engine'),
											  jUrl::get('hfnusearch~admin:index'),
											  100,
											  'hfnusearch');
         $item->icon = $chemin . 'images/admin/search_engine.png';			
         $event->add($item);		 
		}
	} 
}
?>