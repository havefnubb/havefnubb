<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
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
    protected $ranks = array() ;
    /**
     * get the rank from the given nb of messages
     * @param integer $nbMsg nb of messages the user has
     * @return string $ranks the rank corresponding to the nb of messages
     */
    public function getRank($nbMsg) {
        if (!isset($this->ranks[$nbMsg])) {
            $rank = jDao::get('havefnubb~ranks')->getMyRank($nbMsg);

            if (!$rank) {
                $this->ranks[$nbMsg] = jDao::get('havefnubb~ranks')->getHigherRank();
            } else {
                $this->ranks[$nbMsg] = jDao::get('havefnubb~ranks')->getMyRank($nbMsg);
            }
        }
        return $this->ranks[$nbMsg];
    }
}
