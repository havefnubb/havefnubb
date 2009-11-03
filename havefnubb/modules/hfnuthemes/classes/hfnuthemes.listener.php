<?php
/**
* @package   havefnubb
* @subpackage hfnuthemes
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuthemesListener extends jEventListener{
    
   function onHfnuAboutModule ($event) {
        $event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnuthemes')) );
   } 
}