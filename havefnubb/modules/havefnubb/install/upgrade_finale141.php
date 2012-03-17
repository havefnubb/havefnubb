<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2012 Olivier Demah
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_finale141 extends jInstallerModule {
    
    public $targetVersions = array('1.4.1');
    public $date = '2012-03-16';
    
    function install() {
        $ini=new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
        $ini->setValue('version','1.4.1','havefnubb');
        $ini->save();
    }
}
