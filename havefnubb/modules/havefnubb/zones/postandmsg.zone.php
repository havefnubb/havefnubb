<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the nb of post and thread
 */
class postandmsgZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.postandmsg';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){

        $id_forum = $this->param('id_forum');
        if (!$id_forum) return;

        $nbMsg = jDao::get('havefnubb~posts')->countMessages($id_forum);

        $nbThread = jDao::get('havefnubb~threads_alone')->countThreadsByIdForum($id_forum);

        $this->_tpl->assign('nbMsg',$nbMsg);
        $this->_tpl->assign('nbThread',$nbThread);
    }
}
