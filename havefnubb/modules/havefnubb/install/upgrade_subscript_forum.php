<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_subscript_forum extends jInstallerModule {

    public $targetVersions = array('1.4.1a2', '1.5a2');
    public $date = '2011-10-16';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_to_1.4.1_subscript_forum.sql');
        }
    }
}
