<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class what_is_my_rankZone extends jZone {
    protected $_tplname='zone.what_is_my_rank';

    protected function _prepareTpl(){
		$nbMsg = $this->param('nbMsg');
		if (!$nbMsg ) return;		
        $dao = jDao::get('havefnubb~ranks');
        $myRank = $dao->getMyRank($nbMsg);
        $this->_tpl->assign('myRank',$myRank);                
    }
}