<?php
/**
* @package   havefnubb
* @subpackage activeusers_admin
* @author    Laurent Jouanneau
* @copyright 2010-2021 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class activeusersadminListener extends jEventListener{
  
    function onmasterAdminGetDashboardWidget ($event)
    {
        $box = new masterAdminDashboardWidget();
        $box->title = jLocale::get('activeusers_admin~main.server.infos.online.users');
        $box->content = jZone::get('activeusers_admin~activeusers_dashboard');
        $event->add($box);
    }

    function onmasteradminGetMenuContent ($event) {
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
