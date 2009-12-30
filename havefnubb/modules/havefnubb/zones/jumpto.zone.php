<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class jumptoZone extends jZone {
	protected $_tplname='zone.jumpto';

	protected function _prepareTpl(){
		$id_forum = $this->param('id_forum');
		if (!$id_forum) return;

		$form = jForms::create('havefnubb~jumpto',$id_forum);
		$this->_tpl->assign('form',$form);

	}
}
