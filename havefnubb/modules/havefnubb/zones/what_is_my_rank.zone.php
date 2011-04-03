<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * class the displays the rank of one member
 */
class what_is_my_rankZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.what_is_my_rank';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $nbMsg = (int) $this->param('nbMsg');

        $this->_tpl->assign('myRank',
                jClasses::getService('havefnubb~hfnurank')->getRank($nbMsg)
        );
    }
}
