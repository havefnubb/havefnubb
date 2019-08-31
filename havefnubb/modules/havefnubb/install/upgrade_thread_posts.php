<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    Olivier Demah
* @copyright 2008-2012 Olivier Demah
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class havefnubbModuleUpgrader_thread_posts extends jInstallerModule {

    public $targetVersions = array('1.5.0');
    //public $date = '2012-03-16';

    function install() {
        if ($this->firstDbExec()) {
            $this->execSQLScript('sql/upgrade_to_1.4.1_thread_posts');
            // new calcul :
            // we will only count the replies on one side
            // and the thread on another side
            // previously both were added 
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
