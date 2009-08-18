<?php
/**
* @package   havefnubb
* @subpackage hfnuinstall
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuinstallListener extends jEventListener{
    
   function onHfnuAboutModule ($event) {
        $event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnuinstall')) );
   }  
}