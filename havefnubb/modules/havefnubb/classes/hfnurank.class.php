<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* main UI to have a look on ranks
*/
class hfnurank {
    /**
     * content of the ranks
     * @var $ranks array
     */
    public static $ranks = array() ;
    /**
     * get the rank from the given nb of messages
     * @param integer $nbMsg nb of messages the user has
     * @return string $ranks the rank corresponding to the nb of messages
     */
    public static function getRank($nbMsg) {
        if (!isset(self::$ranks[$nbMsg])) {
            $rank = jDao::get('havefnubb~ranks')->getMyRank($nbMsg);
            if (!$rank)
                self::$ranks[$nbMsg] = jDao::get('havefnubb~ranks')->getHigherRank();
            else {
                self::$ranks[$nbMsg] = jDao::get('havefnubb~ranks')->getMyRank($nbMsg);
            }
        }
        return self::$ranks[$nbMsg];
    }
}
