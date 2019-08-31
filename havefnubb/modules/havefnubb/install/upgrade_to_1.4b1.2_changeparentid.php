<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_changeparentid extends jInstallerModule {

    public $targetVersions = array('1.4b1.2');

    
    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_notify')." CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_posts')." CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." CHANGE `parent_id` `thread_id` INT( 12 ) NOT NULL DEFAULT '0'");
        }
    }
}
