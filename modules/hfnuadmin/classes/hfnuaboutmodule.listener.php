<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnuaboutmoduleListener extends jEventListener{
    
    public function onHfnuAboutModule($event) {
        global $gJConfig;
        $modulexml = jClasses::getService('havefnubb~modulexml');
        
        $modulesList = $gJConfig->_modulesPathList;

        foreach($modulesList as $k=>$path) {
            //echo "path $path<br/>";
            if (  preg_match('#/havefnubb/$#',$path,$m) ) 
                if (file_exists($path.'/module.xml')) 
                    $moduleInfos = $modulexml->parse($path.'/module.xml','version');
        }
        $event->Add(array('moduleInfos'=>$moduleInfos));
    }
}
