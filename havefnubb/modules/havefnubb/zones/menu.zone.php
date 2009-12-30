<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class menuZone extends jZone {
	protected $_tplname='zone.menu';

	protected function _prepareTpl(){
		jClasses::inc('havefnubb~hfnuMenuItem');

		$menu = array();
		$items = jEvent::notify('hfnuGetMenuContent')->getResponse();

		foreach ($items as $item) {
			if($item->parentId) {
				if(!isset($menu[$item->parentId])) {
					$menu[$item->parentId] = new hfnuMenuItem($item->parentId, '', '');
				}
				$menu[$item->parentId]->childItems[] = $item;
			}
			else {
				if(isset($menu[$item->id])) {
					$menu[$item->id]->copyFrom($item);
				}
				else {
					$menu[$item->id] = $item;
				}
			}
		}

		usort($menu, "hfnuItemSort");
		foreach($menu as $topitem) {
			usort($topitem->childItems, "hfnuItemSort");
		}
		$this->_tpl->assign('menuitems', $menu);
		$this->_tpl->assign('selectedMenuItem', $this->param('selectedMenuItem',''));
	}
}
