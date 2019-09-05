<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that display menu item to manage the Themes
 */
class hfnusearchadminmenuListener extends jEventListener{
    /**
    * the menu item
    * @param object $event
    * @return void
    */
    function onmasteradminGetMenuContent ($event) {
      $chemin = jApp::urlBasePath().'hfnu/admin/';
      $event->add(new masterAdminMenuItem('hfnusearch',jLocale::get('hfnusearch~search.admin.search.engine'), '', 50));
      if ( jAcl2::check('hfnu.admin.search'))    {
         $item = new masterAdminMenuItem('hfnusearch',
                                              jLocale::get('hfnusearch~search.admin.search.engine'),
                                              jUrl::get('hfnusearch~admin:index'),
                                              100,
                                              'hfnusearch');
         $item->icon = $chemin . 'images/search_engine.png';
         $event->add($item);
        }
    }
}

