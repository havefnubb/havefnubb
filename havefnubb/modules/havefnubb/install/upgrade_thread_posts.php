<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2008-2012 Olivier Demah
* @link      http://www.havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_thread_posts extends jInstallerModule {

    public $targetVersions = array('1.4.1');
    //public $date = '2012-03-16';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_to_1.4.1_thread_posts');
            // @TODO
            // count the posts and thread by forum and put them in nb_msg / nb_thread
            // at the end
            $cn = $this->dbConnection();
            $cn->exec('UPDATE ' . $cn->prefixTable('hfnu_forum').
                        ' SET nb_msg = (SELECT sum(nb_replies) +1 ' .
                        ' FROM ' . $cn->prefixTable('hfnu_threads') .
                        ' WHERE ' . $cn->prefixTable('hfnu_threads') .'.id_forum = '. $cn->prefixTable('hfnu_forum').'.id_forum)' .
                        ', nb_thread = (SELECT count(id_thread) '.
                        ' FROM ' . $cn->prefixTable('hfnu_threads') .
                        ' WHERE ' . $cn->prefixTable('hfnu_threads') .'.id_forum = '. $cn->prefixTable('hfnu_forum').'.id_forum)'
                       );
        }
    }
}
