<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2008-2012 Olivier Demah
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_subscript_forum extends jInstallerModule {

    public $targetVersions = array('1.4.1a2', '1.5.0');
    //public $date = '2012-03-16';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_to_1.4.1_subscript_forum');
        }
    }
}
