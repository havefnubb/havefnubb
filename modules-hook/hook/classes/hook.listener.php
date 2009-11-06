<?php
/**
* @package   havefnubb
* @subpackage hook
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hookListener extends jEventListener{
    
   function onSampleBannerAnnouncement ($event) {
        $event->add( jZone::get('hook~samplebanner_accouncement') );
   } 
}