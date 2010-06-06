<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the check if i read this post
 */
class i_read_this_postZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.i_read_this_post';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $id_post = (int) $this->getParam('id_post');
        $parent_id = (int) $this->getParam('parent_id');
        $id_forum = (int)  $this->getParam('id_forum');

        if ($id_post < 1 or $parent_id < 1 or $id_forum < 1 or !jAuth::isConnected() )
            $bool = true;
        else
            $bool =  jClasses::getService('havefnubb~hfnuread')->getReadPost($id_post,$parent_id,$id_forum);
        $this->_tpl->assign('bool',$bool);
    }
}
