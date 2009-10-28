<?php
/**
* @package   havefnubb
* @subpackage hfnupoll
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminpollmenuListener extends jEventListener{

   /**
   *
   */
	function onmasteradminGetMenuContent ($event) {
	  global $gJConfig;
	  $chemin = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
	  
      if ( jAcl2::check('hfnu.admin.poll.list'))    {
		 $event->add(new masterAdminMenuItem('hfnupoll',jLocale::get('hfnupoll~poll.poll'), '', 100));

		 $item = new masterAdminMenuItem('poll_list',
											  jLocale::get('hfnupoll~poll.the.poll'),
											  jUrl::get('hfnupoll~admin:index'),
											  101,
											  'hfnupoll');
         $item->icon = $chemin . 'images/admin/poll_list.png';			
         $event->add($item);		 
	  }
      /*if ( jAcl2::check('hfnu.admin.poll.add'))    {
		 $item = new masterAdminMenuItem('poll_add',
											  jLocale::get('hfnupoll~poll.add'),
											  jUrl::get('hfnupoll~admin:add'),
											  103,
											  'hfnupoll');
         $item->icon = $chemin . 'images/admin/poll_add.png';
         $event->add($item);		 
	  }*/
	  
	} 
}
?>