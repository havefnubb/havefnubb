<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnucontactListener extends jEventListener{
    
   function onHfnuAboutModule ($event) {
        $event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnucontact')) );
   } 
}