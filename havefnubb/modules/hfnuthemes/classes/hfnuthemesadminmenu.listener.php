<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that display menu item to manage the Themes
 */
class hfnuthemesadminmenuListener extends jEventListener{
    /**
    * the menu item
    * @param object $event
    * @return void
    */
     function onmasteradminGetMenuContent ($event) {
       $chemin = jApp::config()->urlengine['basePath'].'hfnu/admin/';
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

