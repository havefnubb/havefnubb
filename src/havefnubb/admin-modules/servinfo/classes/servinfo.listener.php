<?php
/**
* @package   havefnubb
* @subpackage servinfo
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that manages the jEvent reponses
 */
class servinfoListener extends jEventListener {

    function onmasterAdminGetDashboardWidget ($event) {

        if (jAcl2::check('servinfo.access')) {
            $box = new masterAdminDashboardWidget();
            $box->title = jLocale::get('servinfo~servinfo.infos.title');
            $box->content = jZone::get('servinfo~server_info');
            $event->add($box);
        }
    }
}
