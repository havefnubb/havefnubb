<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class online_offlineZone extends jZone {
	protected $_tplname='zone.online_offline';

	protected function _prepareTpl(){
		$userId = $this->param('userId');
		if (!$userId ) return;

		$dao = jDao::get('havefnubb~timeout');
		$user = $dao->getConnectedByIdUser(time(),$userId);

		if ( $user === false )
			$status = 'offline';
		else
			$status = 'online';

		$this->_tpl->assign('status',$status);
	}
}
