<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuadminListener extends jEventListener{
    
   function onHfnuAboutModule ($event) {
        $event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnuadmin')) );
   }
   
    function onHfnuTaskTodo ($event) {
        
        $dao = jDao::get('havefnubb~notify');
        $notify = $dao->findAll();
        $nbRec = $notify->rowCount();
        if ($nbRec > 0 ) {
            $link = '<a href='.jUrl::get('hfnuadmin~notify:index').'>';
            $link .= jLocale::get('hfnuadmin~task.notification',$nbRec);
            $link .= '</a>';
            $event->add( $link );    
        }
        
    }
}
