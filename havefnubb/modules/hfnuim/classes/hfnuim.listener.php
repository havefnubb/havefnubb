<?php
/**
* @package   havefnubb
* @subpackage hfnuim
* @author    FoxMaSk
* @copyright 2010 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 *Class that manages the jEvent reponses
 */
class hfnuimListener extends jEventListener{
    /**
     * Method that returns the details about its module
     */
    function onHfnuAboutModule ($event) {
        $event->add( jZone::get('hfnuadmin~about',array('modulename'=>'hfnuim')) );
    }
}
