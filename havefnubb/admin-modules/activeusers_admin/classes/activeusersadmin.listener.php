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
        $dao = jDao::get('activeusers~connectedusers');
        $nbMembers = $dao->countConnected(time());
        $label = jLocale::get('activeusers_admin~main.server.infos.online.users');
        $event->add(new serverinfoData('user-online', $label, $nbMembers));
    }
}
