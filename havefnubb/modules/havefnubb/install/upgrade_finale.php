<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2008-2012 Olivier Demah
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_finale extends jInstallerModule {

    public $targetVersions = array('1.5.0');
    //public $date = '2012-03-16';

    function install() {      
        $doc = new DOMDocument;
        $doc->Load (dirname(__FILE__).'/../module.xml');
        $xpath = new DOMXPath($doc);
        $xpath->registerNamespace('jelix',"http://jelix.org/ns/module/1.0");
        $query = "//jelix:module/jelix:info/jelix:version/text()";
        $entries = $xpath->evaluate($query);
        $version = $entries->item(0)->nodeValue;

        $ini=new jIniFileModifier(jApp::configPath().'mainconfig.ini.php');
        $ini->setValue('version',$version,'havefnubb');
        $ini->save();
    }
}
