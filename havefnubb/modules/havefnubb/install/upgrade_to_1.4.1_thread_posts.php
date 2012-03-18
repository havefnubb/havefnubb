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
    public $date = '2012-02-19';

    function install() {
        if ($this->firstDbExec()) {
            $cn = $this->dbConnection();
            $cn->exec("ALTER TABLE ".$cn->prefixTable('hfnu_forum').
                      " ADD nb_msg INT( 12 ) NOT NULL , " .
                      " ADD nb_thread INT( 12 ) NOT NULL ");
            // @TODO
            // count the posts and thread by forum and put them in nb_msg / nb_thread
            // at the end
            
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
