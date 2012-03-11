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
 * Class the displays the main nav bar
 */
class menuZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.menu';
    /**
     *@var boolean $_useCache set the menu in a cache
     */
    protected $_useCache = true;
    /**
     *@var integrer $_cacheTimeout set timeout to 0 to never remove the cache except by the admin or by hand
     */
    protected $_cacheTimeout = 0;
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        jClasses::inc('havefnubb~hfnuMenuItem');
        $admin = (boolean) $this->param('admin');
        $menu = array();
        $items = jEvent::notify('hfnuGetMenuContent',array('admin'=>$admin))->getResponse();

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
