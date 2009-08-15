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
    
    public function onHfnuAboutModule($event) {
        global $gJConfig;
        $modulexml = jClasses::getService('havefnubb~modulexml');
        
        $moduleInfos = $modulexml->parse('hfnuadmin');

        $event->Add(array('moduleInfos'=>$moduleInfos));
    }
}
