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
     * this function mark all forum as read
     */
    public function markAllAsRead() {
        if (jAuth::isConnected()) {
            $id_user = jAuth::getUserSession ()->id;

            // delete all previous forum the current user has read
            $dao = jDao::get('havefnubb~read_forum');
            $dao->deleteByUserId($id_user);

            $dateReadForum = time();
            $forums = jDao::get('havefnubb~forum')->findAll();
            foreach ($forums as $forum) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $forum->id_forum;
                $rec->date_read = $dateReadForum;
                $rec->id_user = $id_user;
                $dao->insert($rec);
            }
            // delete all previous posts the current user has read
            jDao::get('havefnubb~read_posts')->deleteByUserId($id_user);
        }
    }
    /**
     * this function says which forum has been marked as read by which user
     * @param integer $id the forum id
     */
    public function markForumAsRead($id) {
        if ( jAuth::isConnected() ) {
            $id_user = jAuth::getUserSession ()->id;

            $dao = jDao::get('havefnubb~read_forum');
            $exist = $dao->get($id_user,$id);
            if ($exist === false) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $id;
                $rec->date_read = time();
                $rec->id_user = $id_user;
                $dao->insert($rec);
            }
            else {
                $exist->date_read = time();
                $dao->update($exist);
            }
            //delete all the read posts
            $daoReadPost = jDao::get('havefnubb~read_posts');
            $daoReadPost->deleteByUserIdAndIdForum($id_user,$id);
        }
    }
    /**
     * this function save which message from which forum has been read by which user
     * @param record $post record of the current read post
     */
    public function insertReadPost($post,$datePost) {
        if ($post->id_post > 0 and $post->id_forum > 0 and jAuth::isConnected()) {
            $dao = jDao::get('havefnubb~read_posts');
            $id_user = jAuth::getUserSession ()->id;
            $exist = $dao->get($id_user ,$post->id_forum,$post->id_post);
            if ($exist === false) {
                $rec = jDao::createRecord('havefnubb~read_posts');
                $rec->id_forum = $post->id_forum;
                $rec->id_post = $post->id_post;
                $rec->parent_id = $post->parent_id;
                $rec->id_user = $id_user;
                $rec->date_post_read = $datePost;
                $dao->insert($rec);
            }
            else {
                $exist->date_post_read = $datePost;
                $dao->update($exist);
            }
        }
    }
}
