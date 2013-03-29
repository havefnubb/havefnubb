<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008-2011 FoxMaSk
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * Menu Listener to manage the nav bar
 */
class hfnumenuListener extends jEventListener{
    /**
    * Main Menu of the navbar
    * @pararm event $event Object of a listener
    */
    function onhfnuGetMenuContent ($event) {
        $event->add(new hfnuMenuItem('home',
           jLocale::get('havefnubb~main.home'),
           jUrl::get('havefnubb~default:index'),
           1,
           'main'));
        $event->add(new hfnuMenuItem('members',
           jLocale::get('havefnubb~main.member.list'),
           jUrl::get('havefnubb~members:index'),
           2,
           'main'));
        $event->add(new hfnuMenuItem('search',
           jLocale::get('havefnubb~main.search'),
           jUrl::get('hfnusearch~default:index'),
           3,
           'main'));
       if (jApp::config()->havefnubb['rules'] != '') {
           $event->add(new hfnuMenuItem('rules',
               jLocale::get('havefnubb~main.rules'),
               jUrl::get('havefnubb~default:rules'),
               4,
               'main'));
       }
       // dynamic menu
       $menus = jClasses::getService('havefnubb~hfnumenusbar')->getMenus();
        if (!empty($menus)) {
            foreach ($menus as $indx => $menu) {
              $event->add(new hfnuMenuItem($menu['itemName'],
                 $menu['name'],
                 $menu['url'],
                 50 + $menu['order'],
                 'main'));
           }
        }
       if ( $event->getParam('admin') === true) {
           $url = '';
           try {
               // let's try to retrieve the url of the admin, if the admin is in
               // the same app
               $url = jUrl::get('hfnuadmin~default:index');
           }
           catch(Exception $e) {
               if (isset(jApp::config()->havefnubb["admin_url"]))
                   $url = jApp::config()->havefnubb["admin_url"];
           }
           if ($url) {
               $event->add(new hfnuMenuItem('admin',
                  jLocale::get('havefnubb~main.admin.panel'),
                  $url,
                  100,
                  'main'));
           }
       }
   }
}
