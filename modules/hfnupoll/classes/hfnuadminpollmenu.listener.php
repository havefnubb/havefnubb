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
	  
      //if ( jAcl2::check('hfnu.admin.poll.list'))    {
		 $event->add(new masterAdminMenuItem('hfnupoll',jLocale::get('hfnupoll~poll.poll'), '', 100));

		 $item = new masterAdminMenuItem('poll',
											  jLocale::get('hfnupoll~poll.list'),
											  jUrl::get('hfnupoll~admin:index'),
											  100,
											  'hfnupoll');
         $item->icon = $chemin . 'images/admin/poll_list.png';			
         $event->add($item);		 
	  //}
      //if ( jAcl2::check('hfnu.admin.poll.config'))    {
		 $event->add(new masterAdminMenuItem('hfnupoll',jLocale::get('hfnupoll~poll.poll'), '', 101));

		 $item = new masterAdminMenuItem('poll',
											  jLocale::get('hfnupoll~poll.config'),
											  jUrl::get('hfnupoll~admin:config'),
											  101,
											  'hfnupoll');
         $item->icon = $chemin . 'images/admin/poll_config.png';			
         $event->add($item);		 
	  //}
	  
      //if ( jAcl2::check('hfnu.admin.poll.add'))    {
		 $event->add(new masterAdminMenuItem('hfnupoll',jLocale::get('hfnupoll~poll.poll'), '', 102));

		 $item = new masterAdminMenuItem('poll',
											  jLocale::get('hfnupoll~poll.add'),
											  jUrl::get('hfnupoll~admin:add'),
											  102,
											  'hfnupoll');
         $item->icon = $chemin . 'images/admin/poll_add.png';
         $event->add($item);		 
	  //}
	  
	} 
}
?>