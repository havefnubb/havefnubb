<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminListener extends jEventListener{

    function onHfnuTaskTodo ($event) {
        $dao = jDao::get('havefnubb~notify');
        $nbRec = $dao->countAll();
        if ($nbRec > 0 ) {
            $link = '<a href='.jUrl::get('hfnuadmin~notify:index').'>';
            $link .= jLocale::get('hfnuadmin~task.notification', $nbRec);
            $link .= '</a>';
            $event->add( $link );
        }

        $nbRec = jClasses::getService('havefnubb~hfnuposts')->getUnreadThreadByModCount();
        if ($nbRec > 0 ) {
            $link = '<a href='.jUrl::get('hfnuadmin~posts:unread').'>';
            $link .= jLocale::get('hfnuadmin~task.unreadpostbymod', $nbRec);
            $link .= '</a>';
            $event->add( $link );
        }
    }
}
