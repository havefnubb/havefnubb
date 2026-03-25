<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2010-2026 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

use Havefnubb\Havefnubb\Menu\MenuItem;

/**
 * Menu Listener to manage the nav bar
 */
class hfnumenuListener extends jEventListener{
    /**
    * Main Menu of the navbar
    * @pararm event $event Object of a listener
    */
    function onhfnuGetMenuContent ($event) {
        $gJConfig = jApp::config();

        $event->add(new MenuItem('home',
           jLocale::get('havefnubb~main.home'),
           jUrl::get('havefnubb~default:index'),
           1,
           'main'));
        $event->add(new MenuItem('members',
           jLocale::get('havefnubb~main.member.list'),
           jUrl::get('havefnubb~members:index'),
           2,
           'main'));
        $event->add(new MenuItem('search',
           jLocale::get('havefnubb~main.search'),
           jUrl::get('hfnusearch~default:index'),
           3,
           'main'));
       if ($gJConfig->havefnubb['rules'] != '') {
           $event->add(new MenuItem('rules',
               jLocale::get('havefnubb~main.rules'),
               jUrl::get('havefnubb~default:rules'),
               4,
               'main'));
       }
       if ( $event->getParam('admin') === true) {
           $url = '';
           try {
               // let's try to retrieve the url of the admin, if the admin is in
               // the same app
               $url = jUrl::get('hfnuadmin~default:index');
           }
           catch(Exception $e) {
               if (isset($gJConfig->havefnubb["admin_url"]))
                   $url = $gJConfig->havefnubb["admin_url"];
           }
           if ($url) {
               $event->add(new MenuItem('admin',
                  jLocale::get('havefnubb~main.admin.panel'),
                  $url,
                  100,
                  'main'));
           }
       }
   }
}
