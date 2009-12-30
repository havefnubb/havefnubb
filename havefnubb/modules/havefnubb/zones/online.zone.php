<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class onlineZone extends jZone {
	protected $_tplname='zone.online';

	protected function _prepareTpl(){

		$dao = jDao::get('havefnubb~member');
		$members = $dao->findAllConnected(time());
		$nbMembers = $members->rowCount();
		$this->_tpl->assign('members',$members);
		$this->_tpl->assign('nbMembers',$nbMembers);
	}
}
