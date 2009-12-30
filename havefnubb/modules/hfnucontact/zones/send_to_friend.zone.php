<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class send_to_friendZone extends jZone {
	protected $_tplname='zone.send_to_friend';

	protected function _prepareTpl(){
		$action = (string) $this->getParam('action');
		$parms = (array) $this->getParam('parms');
		if ($action == '' or count($parms) == 0)
			return ;

		$url = jUrl::get($action,$parms);
		$this->_tpl->assign('url',$url);
	}
}
