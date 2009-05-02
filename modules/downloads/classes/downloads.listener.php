<?php
/**
* @package      downloads
* @subpackage
* @author       foxmask
* @contributor foxmask
* @copyright    2008 foxmask
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/
class downloadsListener extends jEventListener{

   function onmasteradminGetMenuContent ($event) {
	  global $gJConfig;
	  $chemin = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';      
      $event->add(new masterAdminMenuItem('downloads', jLocale::get('downloads~common.downloads'), '', 29));      
      $item = new masterAdminMenuItem('dls', jLocale::get('downloads~common.list.downloads'), jUrl::get('downloads~mgr:dls'), 31, 'downloads');
      $item->icon = $chemin . 'img/downloads.png';			
      $event->add($item);
        
      $item = new masterAdminMenuItem('config', jLocale::get('downloads~common.configuration'), jUrl::get('downloads~mgr:config'), 32, 'downloads');
      $item->icon = $chemin . 'img/download.png';		
      $event->add($item);
        
      $item = new masterAdminMenuItem('manage', jLocale::get('downloads~common.add_a_download'), jUrl::get('downloads~mgr:manage'), 33, 'downloads');
      $item->icon = $chemin . 'img/get_download.png';			
      $event->add($item);	        
   }
   
   function onmasterAdminGetDashboardWidget ($event) {
		$box = new masterAdminDashboardWidget();
		$box->title = jLocale::get('downloads~common.downloads');
		$box->content = jZone::get('downloads~admin');
		$event->add($box);
   }   
}
?>
