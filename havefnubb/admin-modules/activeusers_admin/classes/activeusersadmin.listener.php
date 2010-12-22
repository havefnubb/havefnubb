<?php
/**
* @package   havefnubb
* @subpackage activeusers_admin
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class activeusersadminListener extends jEventListener{
  
    function onservinfoGetInfo($event) {
        $nbMembers = jClasses::create('activeusers~connectedusers')->getCount();
        $label = jLocale::get('activeusers_admin~main.server.infos.online.users');
        $event->add(new serverinfoData('user-online', $label, $nbMembers));
    }

    function onmasteradminGetMenuContent ($event) {
        global $gJConfig;

        if (jAcl2::check('activeusers.configuration')) {
            $item = new masterAdminMenuItem('activeusers',
                                            jLocale::get('activeusers_admin~main.masteradmin.menu.item'),
                                            jUrl::get('activeusers_admin~default:index'),
                                            120,
                                            'system');
            $event->add($item);
        }
    }
}
