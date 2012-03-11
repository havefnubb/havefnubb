<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence   http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the manage the Rates on a "source" (articles/news/blog post/etc...)
 */
class rates {

    /**
     * get the Rate of a given source and ID
     * @param integer $id_source the id to link to the source
     * @param string $source the linked source
     * @return integer $total the global rate
     */
    function getTotalRatesBySource($id_source, $source) {
        $cnx = jDb::getConnection();
        $strQuery = 'SELECT COUNT(*) as total_rates, SUM(level) as total_level, AVG(level) as avg_level ' .
                    ' FROM '.$cnx->prefixTable('hfnu_rates').
                    " WHERE id_source = '".$id_source."' AND source='".addslashes($source). "' GROUP BY id_source";
        $rs = $cnx->query($strQuery);
        $total = $rs->fetch();

        return $total;
    }

    /**
     * save the Rate to a given source and ID
     * @param integer $id_source the id to link to the source
     * @param string $source the linked source
     * @param integer $rate the rate
     * @return boolean
     */
    function saveRatesBySource($id_source, $source, $rate) {

        $dao = jDao::get('hfnurates~rates');
        $id_user =  jAuth::isConnected() ? 0 : jAuth::getUserSession ()->id;

        $rec = $dao->getByIdSourceSourceRate($id_user, $id_source, $source);

        if ($rec == false) {
            $record = jDao::createRecord('hfnurates~rates');
            $record->id_source  = $id_source;
            $record->id_user    = $id_user;
            $record->source     = $source;
            $record->level      = $rate;
            $record->ip         = $_SERVER['REMOTE_ADDR'];
            $dao->insert($record);
        } else {
            $rec->level = $rate;
            $dao->update($rec);
        }
        jZone::clear("hfnurates~rates");
        return true;

    }
}
