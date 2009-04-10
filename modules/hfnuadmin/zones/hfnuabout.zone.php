<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuaboutZone extends jZone {
    protected $_tplname='zone.hfnuabout';

    //@TODO : notify an event that will "respond"
    // "i am the module xxx here is my description"
    
    protected function _prepareTpl(){
        
        // $data will contain the complet content of
        // module.xml of each module that will respond
        $data = jEvent::notify('HfnuAboutModule');        
    }
}