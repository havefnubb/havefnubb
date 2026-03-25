<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
 * @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2019-2026 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Havefnubb\Havefnubb\Members\MessagesRank;

/**
 * class that displays the rank of one member
 */
class what_is_my_rankZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.what_is_my_rank';
    /**
     *@var boolean $_useCache set the zone in a cache
     */
    protected $_useCache = true;
    /**
     *@var integer $_cacheTimeout set timeout to one hour
     */
    protected $_cacheTimeout = 3600;
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $nbMsg = (int) $this->param('nbMsg');

        $this->_tpl->assign('myRank',
            (new MessagesRank())->getRank($nbMsg)
        );
    }
}
