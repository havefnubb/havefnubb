<?php
/**
* main UI to have a look on ranks
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class hfnurank {
    /**
     * content of the ranks
     * var $ranks array
     */    
    public static $ranks = array() ;    
    /**
     * get the rank from the given nb of messages
     * @param $nbMsg integer nb of messages the user has
     * @return $ranks string the rank corresponding to the nb of messages
     */
    public static function getRank($nbMsg) {
        if (!isset(self::$ranks[$nbMsg])) 
            self::$ranks[$nbMsg] = jDao::get('havefnubb~ranks')->getMyRank($nbMsg);
        return self::$ranks[$nbMsg];
    }
    
}