<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminmenuListener extends jEventListener{

    function onmasteradminGetMenuContent ($event) {
        global $gJConfig;
        $chemin = $gJConfig->urlengine['basePath'].'hfnu/admin/';
        if ( jAcl2::check('hfnu.admin.index')) {
         $event->add(new masterAdminMenuItem('havefnubb','HaveFnu BB!', '', 20));
        }
        if ( jAcl2::check('hfnu.admin.config'))    {
            $item = new masterAdminMenuItem('config',
                                            jLocale::get('hfnuadmin~admin.config'),
                                            jUrl::get('hfnuadmin~default:config'),
                                            201,
                                            'havefnubb');
            $item->icon = $chemin . 'images/config.png';
            $event->add($item);
        }
        if ( jAcl2::check('hfnu.admin.category')) {
            $item = new masterAdminMenuItem('category',
                                            jLocale::get('hfnuadmin~admin.categories'),
                                            jUrl::get('hfnuadmin~category:index'),
                                            203,
                                            'havefnubb');
            $item->icon = $chemin . 'images/category.png';
            $event->add($item);
        }
        if ( jAcl2::check('hfnu.admin.forum')) {
            $item = new masterAdminMenuItem('forum',
                                            jLocale::get('hfnuadmin~admin.forum'),
                                            jUrl::get('hfnuadmin~forum:index'),
                                            204,
                                            'havefnubb');
            $item->icon = $chemin . 'images/forum.png';
            $event->add($item);
        }
        if ( jAcl2::check('hfnu.admin.post')) {
            $item = new masterAdminMenuItem('notify',
                                            jLocale::get('hfnuadmin~admin.notifying'),
                                            jUrl::get('hfnuadmin~notify:index'),
                                            206,
                                            'havefnubb');
            $item->icon = $chemin . 'images/notification.png';
            $event->add($item);
        }
        if ( jAcl2::check('hfnu.admin.member')) {
            $item = new masterAdminMenuItem('ranks',
                                            jLocale::get('hfnuadmin~admin.rank'),
                                            jUrl::get('hfnuadmin~ranks:index'),
                                            207,
                                            'havefnubb');
            $item->icon = $chemin . 'images/rank.png';
            $event->add($item);
            $item = new masterAdminMenuItem('ban',
                                            jLocale::get('hfnuadmin~admin.ban'),
                                            jUrl::get('hfnuadmin~ban:index'),
                                            208,
                                            'havefnubb');
            $item->icon = $chemin . 'images/ban.png';
            $event->add($item);
        }
    }

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

    function onmasteradminGetInfoBoxContent ($event) {
        if ( jAcl2::check('hfnu.admin.index'))    {
            $event->add(new masterAdminMenuItem('portal',
                jLocale::get('hfnuadmin~admin.back.to.havefnubb'),
                jUrl::get('havefnubb~default:index'),
                100,
                'havefnubb'));
        }
    }
}
