<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadmindashboardListener extends jEventListener {

    function onmasterAdminGetDashboardWidget ($event) {

        if (jAcl2::check('servinfo.access')) {
            $box = new masterAdminDashboardWidget();
            $box->title = jLocale::get('hfnuadmin~admin.system.infos');
            $box->content = jZone::get('servinfo~server_info');
            $event->add($box);
        }

        $box = new masterAdminDashboardWidget();
        $box->title = jLocale::get('hfnuadmin~task.todo');
        $box->content = jZone::get('hfnuadmin~hfnutasktodo');
        $event->add($box);

    }

}
