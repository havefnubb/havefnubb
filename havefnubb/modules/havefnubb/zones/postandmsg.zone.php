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

        //user has the admin.post right : get all the posts
        if (jAcl2::check('hfnu.admin.post')) {
            $daoThreads = jDao::get('havefnubb~threads_alone');
            $msgs = $daoThreads->countMessagesByIdForum($id_forum);
            $nbThread = $daoThreads->countThreadsByIdForum($id_forum);
            $nbMsg = $msgs->nb_replies + $msgs->total_replies;
        //user has not the admin.post right : get only the non hidden posts
        } else {
            $daoThreads = jDao::get('havefnubb~threads_alone');
            $msgs = $daoThreads->countVisibleMessagesByIdForum($id_forum);
            $nbThread = $daoThreads->countVisibleThreadsByIdForum($id_forum);
            $nbMsg = $msgs->nb_replies + $msgs->total_replies;
        }

        $this->_tpl->assign('nbMsg',$nbMsg);
        $this->_tpl->assign('nbThread',$nbThread);
    }
}
