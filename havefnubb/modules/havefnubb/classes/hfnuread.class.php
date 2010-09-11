<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
* main UI to manage the statement of the forums of HaveFnuBB!
*/
class hfnuread {
    /**
     * the read posts
     * @var $postsRead array
     */
    public static $postsRead = array();
    /**
     * this function mark all forum as read
     */
    public static function markAllAsRead() {
        if (jAuth::isConnected()) {
            $user = jAuth::getUserSession();
            $dao = jDao::get('havefnubb~member');
            $rec = $dao->get($user->login);

            $lastConnect = jDao::get('havefnubb~connected')->get($user->id)->connected;
            if ($lastConnect !== null) {
                //update the user data ...
                $rec->member_last_connect = $lastConnect;
                $dao->update($rec);
                // ... and update the current user session
                jAuth::updateUser($rec);
            }
        }
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public static function getReadForum($id_forum) {
        if ($id_forum < 1 ) return true;
        if (!jAuth::isConnected() ) return true;

        if (
            jDao::get('havefnubb~forum')->get($id_forum)->date_last_msg >
            jAuth::getUserSession()->member_last_connect
            )
            return false;
        else
            return true;
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param  integer $id_post the current id post
     * @param  integer $parent_id the parent id (thread id)
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public static function getReadPost($id_post,$parent_id,$id_forum) {
        if ($id_post < 1 ) return true;
        if ($parent_id < 1 ) return true;
        if ($id_forum < 1 ) return true;
        if ( !jAuth::isConnected() ) return true;

        if (  jAcl2::check('hfnu.admin.post') )
            $postRead = jDao::get('havefnubb~threads')->getUserLastCommentOnPosts($parent_id);
        else
            $postRead = jDao::get('havefnubb~threads')->getUserLastVisibleCommentOnPosts($parent_id);

        if ($postRead === false)
            return false;
        else {
            if ($postRead->date_last_post > jAuth::getUserSession()->member_last_connect)
                return false;
            else
                return true;
        }
    }
    /**
     * this function get the messages that the current user does not read yet between his last connection and last 15min
     * then return the given record
     * @return array : the limited records + total of records
     */
    public static function findUnreadThread($page=0,$nbPostPerPage=25) {
        $posts = array();
        if ( !jAuth::isConnected() )
            return $posts;
        $memberLastConnect = jAuth::getUserSession()->member_last_connect;

        if (  jAcl2::check('hfnu.admin.post') ) {
                $posts = jDao::get('havefnubb~threads')
                        ->findUnreadThread(
                            $memberLastConnect,
                            $page,
                            $nbPostPerPage);
        }
        else {
                $posts = jDao::get('havefnubb~threads')
                        ->findUnreadThreadVisible(
                            $memberLastConnect,
                            $page,
                            $nbPostPerPage);
        }
        return $posts;
    }
}
