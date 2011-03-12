<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Laurent Jouanneau
* @copyright 2011 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_readdate extends jInstallerModule {

    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("DELETE FROM TABLE ".$cn->prefixTable('hfnu_read_posts')." ");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." DROP PRIMARY KEY");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." DROP id_post");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." DROP INDEX id_user");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." DROP INDEX id_forum");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." DROP INDEX date_read");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_posts')." ADD PRIMARY KEY (id_user, id_forum, thread_id)");

            $cn->exec("DELETE FROM TABLE ".$cn->prefixTable('hfnu_read_forum')." ");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_forum')." DROP PRIMARY KEY");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_forum')." DROP INDEX id_user");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_forum')." DROP INDEX id_forum");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_forum')." DROP INDEX date_read");
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_read_forum')." ADD PRIMARY KEY (id_user, id_forum)");
        }
    }
}
