<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class online_offlineZone extends jZone {
    protected $_tplname='zone.online_offline';

    protected function _prepareTpl(){
		$userId = $this->param('userId');
		if (!$userId ) return;
		
        $dao = jDao::get('havefnubb~member');
        $user = $dao->getById($userId);
		
		$dt = new jDateTime();
		$dt->setFromString($user->member_last_connect,jDateTime::DB_DTFORMAT);

		$dt2 = new jDateTime();
		$dt2->setFromString(date('Y-m-d H:i:s'),jDateTime::DB_DTFORMAT);
		
		$duration = $dt->durationTo($dt2);

		if ( $duration->seconds <= 300 )
			$status = jLocale::get('havefnubb~main.common.yes');
		else
			$status = jLocale::get('havefnubb~main.common.no');					
		
        $this->_tpl->assign('status',$status);                
    }
}