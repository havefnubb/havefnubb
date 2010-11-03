<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008 FoxMaSk, 2010 laurent jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class modulesinfoListener extends jEventListener{

    function onmasteradminGetMenuContent ($event) {
        global $gJConfig;
        $chemin = $gJConfig->urlengine['basePath'].'hfnu/admin/';
        if ( jAcl2::check('modulesinfo.access')) {
            $item = new masterAdminMenuItem('modulesinfo',
                                            jLocale::get('modulesinfo~modulesinfo.masteradmin.menu.item'),
                                            jUrl::get('modulesinfo~default:index'),
                                            202,
                                            'system');
            $item->icon = $chemin . 'images/modules_list.png';
            $event->add($item);
        }
    }
}
